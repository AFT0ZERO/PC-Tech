import argparse
import json
import logging
import sys
import time
from pathlib import Path

# Ensure logs dir exists before setting up logger
log_dir = Path(__file__).parent / 'logs'
log_dir.mkdir(exist_ok=True)

# Setup simple logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler(log_dir / 'scraper.log'),
        logging.StreamHandler(sys.stdout)
    ]
)
logger = logging.getLogger('main')

from scrapers.static_scraper import StaticScraper
from scrapers.dynamic_scraper import DynamicScraper

def load_config():
    config_path = Path(__file__).parent / 'config.json'
    if not config_path.exists():
        logger.error(f"Config file not found at {config_path}")
        sys.exit(1)
    with open(config_path, 'r', encoding='utf-8') as f:
        return json.load(f)

def run_scraper():
    parser = argparse.ArgumentParser(description="Python Price Scraper")
    parser.add_argument("--store", type=str, help="Scrape only a specific store by name", default=None)
    args = parser.parse_args()

    logger.info("Starting Price Scraper Run")
    config = load_config()
    stores_to_run = config.get('stores', [])

    if args.store:
        stores_to_run = [s for s in stores_to_run if s['store_name'].lower() == args.store.lower()]
        if not stores_to_run:
            logger.error(f"Store '{args.store}' not found in config.json")
            sys.exit(1)

    for store_cfg in stores_to_run:
        logger.info(f"--- Processing Store: {store_cfg['store_name']} ---")
        mode = store_cfg.get('mode', 'static')
        
        try:
            if mode == 'dynamic':
                import asyncio
                scraper = DynamicScraper(store_cfg)
                asyncio.run(scraper.run())
            else:
                scraper = StaticScraper(store_cfg)
                scraper.run()
        except Exception as e:
            logger.exception(f"Fatal error while scraping store {store_cfg['store_name']}: {e}")

    logger.info("Scraper Run Completed.")

if __name__ == "__main__":
    run_scraper()
