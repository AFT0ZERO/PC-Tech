import logging
import asyncio
from playwright.async_api import async_playwright, TimeoutError
from .base import BaseScraper

logger = logging.getLogger('main.scrapers.dynamic')

class DynamicScraper(BaseScraper):
    def __init__(self, config):
        super().__init__(config)

    async def fetch_price(self, page, url, retries=3):
        for attempt in range(retries):
            try:
                # Wait until network is idle or domcontentloaded
                await page.goto(url, timeout=30000, wait_until='domcontentloaded')
                
                # Wait up to 10 seconds for any of the valid selectors to appear
                combined_selector = ", ".join(self.price_selectors)
                if not combined_selector:
                    return None
                    
                try:
                    await page.wait_for_selector(combined_selector, timeout=10000)
                    
                    # Now check them strictly in order of priority (fallback)
                    for selector in self.price_selectors:
                        element = await page.query_selector(selector)
                        if element:
                            text = await element.inner_text()
                            return text
                except TimeoutError:
                    logger.warning(f"[{self.store_name}] All selectors {self.price_selectors} timed out on {url}")
                    return None
                    
            except Exception as e:
                logger.warning(f"[{self.store_name}] Attempt {attempt+1} failed for {url}: {e}")
                if attempt < retries - 1:
                    await asyncio.sleep(2 ** attempt)  # Exponential backoff
        
        logger.error(f"[{self.store_name}] Failed to extract price from {url} after {retries} attempts.")
        return None

    async def run(self):
        logger.info(f"[{self.store_name}] Starting Dynamic Scrape (Playwright) for {len(self.products)} products.")
        
        async with async_playwright() as p:
            # Launch headless chromium
            browser = await p.chromium.launch(headless=True)
            context = await browser.new_context(
                user_agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            )
            page = await context.new_page()

            for prod in self.products:
                part_id = prod['part_id']
                url = prod['url']
                
                logger.info(f"[{self.store_name}] Rendering Product {part_id}: {url}")
                raw_text = await self.fetch_price(page, url)
                
                if not raw_text:
                    self.save_to_db(part_id, url, None)
                else:
                    clean_val = self.clean_price(raw_text)
                    self.save_to_db(part_id, url, clean_val)
                
                # Politeness delay
                logger.debug(f"[{self.store_name}] Sleeping for {self.delay} seconds.")
                await asyncio.sleep(self.delay)

            await browser.close()
