# Python Price Scraper

A standalone microservice that automatically collects PC part prices from multiple online stores and stores them in the shared MySQL database used by the Laravel PC Parts Comparison Platform.

## Requirements

- Python 3.11+
- Google Chrome (installed automatically via Playwright)

## Setup

1. Install dependencies:
   ```bash
   pip install -r requirements.txt
   playwright install chromium
   ```

2. Configure Database:
   - Edit the `.env` file in this directory to match your Laravel database credentials.

3. Configure Stores & Products:
   - Edit `config.json` to define target stores, URLs, and CSS selectors.
   - Set `mode` to `"static"` for standard HTML pages or `"dynamic"` for JavaScript-rendered React/Vue/Angular pages.

## Usage

Run the scraper for all stores defined in config:
```bash
python scraper.py
```

Run for a specific store only:
```bash
python scraper.py --store amazon
```

## Scheduling

It's recommended to run this script automatically every 6 hours via Linux `cron` or Windows Task Scheduler.
Alternatively, it can be triggered manually from the Laravel Admin Panel using the `php artisan scraper:run` command.
