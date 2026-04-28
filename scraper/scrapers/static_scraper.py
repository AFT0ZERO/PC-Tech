import logging
import time
import requests
from bs4 import BeautifulSoup
from .base import BaseScraper

logger = logging.getLogger('main.scrapers.static')

class StaticScraper(BaseScraper):
    def __init__(self, config):
        super().__init__(config)
        self.headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        }
        
    def fetch_url(self, url, retries=3):
        for attempt in range(retries):
            try:
                response = requests.get(url, headers=self.headers, timeout=15)
                response.raise_for_status()
                return response.text
            except requests.RequestException as e:
                logger.warning(f"[{self.store_name}] Attempt {attempt+1} failed for {url}: {e}")
                if attempt < retries - 1:
                    time.sleep(2 ** attempt)  # Exponential backoff
        logger.error(f"[{self.store_name}] Failed to fetch {url} after {retries} attempts.")
        return None

    def run(self):
        logger.info(f"[{self.store_name}] Starting Static Scrape for {len(self.products)} products.")
        for prod in self.products:
            part_id = prod['part_id']
            url = prod['url']
            
            logger.info(f"[{self.store_name}] Fetching Product {part_id}: {url}")
            html = self.fetch_url(url)
            
            if not html:
                self.save_to_db(part_id, url, None)
                continue
                
            soup = BeautifulSoup(html, 'lxml')
            
            price_elem = None
            for selector in self.price_selectors:
                price_elem = soup.select_one(selector)
                if price_elem:
                    break
            
            if not price_elem:
                logger.warning(f"[{self.store_name}] None of the selectors {self.price_selectors} found for Product {part_id}.")
                self.save_to_db(part_id, url, None)
                continue
                
            raw_text = price_elem.get_text(strip=True)
            clean_val = self.clean_price(raw_text)
            
            self.save_to_db(part_id, url, clean_val)
            
            # Politeness delay
            logger.debug(f"[{self.store_name}] Sleeping for {self.delay} seconds.")
            time.sleep(self.delay)
