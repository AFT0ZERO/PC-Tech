import logging
import re
from abc import ABC, abstractmethod
from db.writer import insert_price_history

logger = logging.getLogger('main.scrapers.base')

class BaseScraper(ABC):
    def __init__(self, config):
        self.store_name = config.get('store_name')
        self.base_url = config.get('base_url')
        
        self.price_selectors = config.get('price_selectors', [])
        single_sel = config.get('price_selector')
        if single_sel and single_sel not in self.price_selectors:
            self.price_selectors.insert(0, single_sel)
            
        self.currency = config.get('currency', 'JOD')
        self.delay = config.get('delay', 3)
        self.products = config.get('products', [])
        
    def clean_price(self, price_str):
        """
        Strips away currency symbols and text, returning just the float value.
        Example: 'JD 25.99' -> 25.99
        """
        if not price_str:
            return None
            
        # Extract the first sequence of digits, commas, and dots
        match = re.search(r'[\d,]+\.?\d*', str(price_str))
        if match:
            clean_str = match.group(0).replace(',', '')
            try:
                return float(clean_str)
            except ValueError:
                return None
        return None

    def save_to_db(self, product_id, url, price):
        if price is None:
            logger.warning(f"[{self.store_name}] Skipping DB insert for Product {product_id}: No valid price.")
            insert_price_history(product_id, self.store_name, url, 0, self.currency, 'failed')
            return False
            
        logger.info(f"[{self.store_name}] Saving Product {product_id}: {self.currency} {price}")
        return insert_price_history(product_id, self.store_name, url, price, self.currency, 'ok')

    @abstractmethod
    def run(self):
        """
        Orchestrates the scraping flow for all products defined in config.
        Must be implemented by child classes.
        """
        pass
