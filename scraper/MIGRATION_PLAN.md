# PC-Tech Scraper: Python-to-PHP Migration Plan

---

## 1. Architecture Assessment

### 1.1 System Overview

The scraper system has **two halves** that currently span Python and PHP:

| Layer | Technology | Purpose |
|---|---|---|
| **Orchestration** | PHP/Laravel | Admin UI, scheduler, config sync, stats |
| **Execution** | Python 3.13 | Actual HTTP scraping, HTML parsing, price extraction, DB writes |

The PHP layer shells out to Python via `exec()`. This is architecturally fragile—two separate dependency stacks, two runtimes, and a process boundary bridged only by stdout string capture.

### 1.2 Current Data Flow

```
┌───────────────────────┐
│  Admin Dashboard (Blade)  │  POST /dashboard/scraper/run
└──────┬────────────────┘
       │ ScraperController::run()
       ▼
┌───────────────────────┐
│  ScraperRunnerService   │  Artisan::call('scraper:run')
└──────┬────────────────┘
       │
       ▼
┌───────────────────────┐
│  RunScraperCommand (PHP) │  exec("python scraper.py --store=X 2>&1")
└──────┬────────────────┘
       │ process spawn
       ▼
┌──────────────────────────────────────────────────┐
│                 Python scraper.py                 │
│                                                   │
│  Loads config.json → iterates stores → dispatches │
│                                                   │
│  ┌──────────────────┐   ┌───────────────────┐    │
│  │  StaticScraper    │   │  DynamicScraper    │    │
│  │  (requests+BS4)  │   │  (Playwright/Cr)   │    │
│  └───────┬──────────┘   └───────┬───────────┘    │
│          │ clean_price()        │ clean_price()   │
│          │ save_to_db()         │ save_to_db()    │
│          └─────────┬────────────┘                 │
│                    ▼                              │
│          ┌──────────────────┐                     │
│          │  writer.py        │                     │
│          │  INSERT price_    │                     │
│          │  history           │                     │
│          │  UPDATE store_     │                     │
│          │  product           │                     │
│          └─────────┬─────────┘                     │
└────────────────────┼──────────────────────────────┘
                     │ mysql-connector-python
                     ▼
              ┌──────────────┐
              │  MySQL DB    │
              │  pc_tech     │
              └──────────────┘
```

### 1.3 Key Architectural Problems

1. **Dual runtime dependency** — Python required alongside PHP
2. **No feedback loop** — `exec()` captures stdout as string; no structured error reporting
3. **Wasted retries on placeholders** — `"#"` URLs retry 3 times before failing (see log: lines 111-196 waste ~2.5 minutes on dead URLs)
4. **Config sync out of band** — `ScraperConfigService` writes `config.json` separately; no atomic consistency between sync and scrape
5. **No scheduling** — `Kernel::schedule()` is empty; scraper is manual-only despite README recommending 6-hour cron
6. **Process ownership** — A long-running `exec()` blocks the PHP request; admin UI will time out during full runs

---

## 2. Current Scraper Analysis

### 2.1 File Inventory

```
scraper/
├── scraper.py                    # CLI entry point
├── config.json                   # Store/product/selector definitions (4 stores, ~47 real URLs)
├── components.sqlite             # SQLite index of component specs
├── build_db_index.py             # Builds components.sqlite from JSON files
├── requirements.txt              # 6 Python packages
├── README.md
├── logs/
│   └── scraper.log
├── scrapers/
│   ├── __init__.py
│   ├── base.py                   # Abstract BaseScraper + clean_price + save_to_db
│   ├── static_scraper.py         # requests + BeautifulSoup (lxml)
│   └── dynamic_scraper.py        # Playwright (headless Chromium)
└── db/
    ├── __init__.py
    ├── connection.py             # MySQL connection via mysql-connector-python + .env
    └── writer.py                 # insert_price_history() + UPDATE store_product
```

### 2.2 Store-by-Store Analysis

| # | Store | Mode | Type | Base URL | # Products (real/total) | Delay | Selectors |
|---|---|---|---|---|---|---|---|
| 1 | Midas Computer Center | `static` | requests + BS4 | mcc-jo.com | 12/30 | 2s | `.special-price`, `.product-price` |
| 2 | Number One Store | `dynamic` | Playwright | numberonestore.net | 3/30 | 3s | `.product-price-new`, `.product-price` |
| 3 | City Center | `dynamic` | Playwright | citycenter.jo | 13/30 | 7s | `.price-new > .tb_integer`, `.price-regular > .tb_integer` |
| 4 | Oriental Store | `dynamic` | Playwright | os-jo.com | 19/30 | 7s | `.price-new > .tb_integer`, `.price-regular > .tb_integer` |

**Key observations:**

- City Center and Oriental Store use the **exact same selectors** — both are likely OpenCart-based stores
- `"#"` URLs are placeholders for products not yet configured — all 4 stores have many of these (73 total across all stores)
- 47 real product URLs across 4 stores
- Only Midas Computer Center uses static scraping; the other 3 require JavaScript rendering

### 2.3 Per-Scraper Behavioral Analysis

#### StaticScraper (`scraper.py → StaticScraper`)

- **Website**: mcc-jo.com (Magento 2)
- **HTTP strategy**: `requests.get()` with desktop Chrome UA, 15s timeout
- **HTML parsing**: `BeautifulSoup(html, 'lxml')` → `soup.select_one(selector)` — tries selectors in priority order
- **Retry**: 3 attempts, exponential backoff (2^attempt seconds)
- **Error handling**: Catches `requests.RequestException`; falls through to `save_to_db(product_id, url, None)` on failure
- **Logging**: Per-product INFO/WARNING/ERROR via Python `logging` module
- **DB writes**: Via shared `save_to_db()` → `insert_price_history()`

#### DynamicScraper (`scraper.py → DynamicScraper`)

- **Websites**: numberonestore.net, citycenter.jo, os-jo.com (OpenCart / JS-rendered pages)
- **Browser strategy**: Headless Chromium via Playwright, single browser context reused across all products in a store
- **Page strategy**: `page.goto(url, timeout=30000, wait_until='domcontentloaded')` → `page.wait_for_selector(combined_selectors, timeout=10000)` → `element.inner_text()`
- **Retry**: 3 attempts per product, exponential backoff (async `asyncio.sleep(2**attempt)`)
- **Error handling**: Catches generic `Exception` and `TimeoutError`
- **Logging**: Same as StaticScraper
- **DB writes**: Same shared `save_to_db()`

### 2.4 Shared Components

| Component | File:Line | Role |
|---|---|---|
| `BaseScraper` | `scrapers/base.py:8` | Abstract base: holds config, `clean_price()`, `save_to_db()` |
| `clean_price()` | `scrapers/base.py:22` | Regex `[\d,]+\.?\d*` → float extraction |
| `save_to_db()` | `scrapers/base.py:40` | Calls `insert_price_history()`; returns bool |
| `insert_price_history()` | `db/writer.py:6` | MySQL: INSERT price_history + UPDATE store_product |
| `get_connection()` | `db/connection.py:10` | `mysql.connector.connect()` via `.env` |
| `config.json` | `scraper/config.json` | Store/product/selector definitions |
| `build_db_index.py` | `scraper/build_db_index.py` | Builds `components.sqlite` from `buildcores-db/open-db/` JSON files |

### 2.5 Logging Analysis

- Python `logging.basicConfig` to `logs/scraper.log` + stdout
- Format: `%(asctime)s - %(name)s - %(levelname)s - %(message)s`
- Logger hierarchy: `main` → `main.scrapers.base` / `main.scrapers.static` / `main.scrapers.dynamic` / `main.db.connection` / `main.db.writer`
- Log file shows successful runs but also a **critical bug**: placeholder `"#"` URLs waste ~45 seconds per store retrying 3x each before giving up

### 2.6 Database Interactions

**Tables written to:**

1. **`price_history`** — INSERT with `sp_id`, `price`, `currency`, `status` ('ok' | 'failed')
2. **`store_product`** — UPDATE `product_price` (sync with latest scrape), UPDATE `product_url`

**Tables read from:**

1. **`store_product`** JOIN `stores` — look up `sp_id` via `product_id` + `store_name`

**SQLite component database** (`components.sqlite`):

- Built by `build_db_index.py` from `buildcores-db/open-db/` JSON files
- Schema: `components(id TEXT PK, category TEXT, name TEXT, search_text TEXT, specs_json TEXT)`
- Indexed on `search_text`
- Consumed by `BackfillSpecs` Artisan command (not the scraper itself — no migration needed)

### 2.7 PHP Integration Points (Current)

| File | Role |
|---|---|
| `app/Console/Commands/RunScraperCommand.php` | Thin wrapper: `exec("python scraper.py ... 2>&1")` |
| `app/Services/ScraperRunnerService.php` | Calls `Artisan::call('scraper:run')` |
| `app/Services/ScraperConfigService.php` | Syncs MySQL `store_product` into `config.json` |
| `app/Repositories/ScraperConfigStore.php` | Low-level JSON read/write + DB queries |
| `app/Repositories/PriceHistoryRepository.php` | Reads `price_history` for stats display |
| `app/Http/Controllers/ScraperController.php` | Admin dashboard routes (GET/POST) |
| `app/Models/PriceHistory.php` | Eloquent model for `price_history` |
| `app/Console/Kernel.php` | Empty `schedule()` — no cron registered |

---

## 3. Dependency Mapping

### 3.1 Python Dependencies

| Python Package | Version | Purpose | PHP Replacement |
|---|---|---|---|
| `requests` | >=2.31.0 | HTTP client for static scraping | Laravel HTTP Client (`Illuminate\Support\Facades\Http`) |
| `beautifulsoup4` | >=4.12.0 | HTML parsing (CSS selectors) | `symfony/dom-crawler` + `symfony/css-selector` |
| `playwright` | >=1.40.0 | Headless browser for JS rendering | `chrome-php/chrome` |
| `mysql-connector-python` | >=8.3.0 | MySQL direct connection | Laravel Eloquent / Query Builder (already present) |
| `python-dotenv` | >=1.0.0 | Environment variable loading | Laravel's built-in `.env` handling |
| `lxml` | >=5.0.0 | Fast HTML parser for BS4 | Handled by `symfony/dom-crawler` internally |

### 3.2 PHP Dependencies (Existing)

| PHP Package | Purpose | Status |
|---|---|---|
| Laravel 11.x | Framework | Already present |
| Eloquent ORM | MySQL access | Already present |
| `symfony/dom-crawler` | HTML parsing (transitive via Laravel) | Likely present |
| `symfony/css-selector` | CSS selector support | May need explicit install |

### 3.3 External Dependencies

| Dependency | Python | PHP |
|---|---|---|
| Chromium browser | Installed via `playwright install chromium` | Same binary — needed for dynamic scraping via `chrome-php/chrome` |
| MySQL server | Via `mysql-connector-python` | Via Laravel PDO (already configured) |
| SQLite (for components.sqlite) | `sqlite3` stdlib module | PHP `SQLite3` extension or PDO sqlite |

### 3.4 Config Dependencies

| Config File | Consumer | Sync Source |
|---|---|---|
| `scraper/config.json` | Python `scraper.py` | `ScraperConfigService` writes from `store_product` MySQL table |
| `scraper/.env` | Python `db/connection.py` | Mirrors Laravel `.env` (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) |

---

## 4. Proposed PHP Architecture

### 4.1 Directory Structure

```
app/
├── Scraping/
│   ├── Contracts/
│   │   └── ScraperInterface.php          # Contract: scrape(StoreConfig $config): void
│   ├── Scrapers/
│   │   ├── StaticScraper.php             # HTTP + DOM Crawler
│   │   └── DynamicScraper.php            # Chrome headless via process
│   ├── BaseScraper.php                   # Abstract: cleanPrice(), saveToDb(), delay, shouldSkip()
│   ├── ScraperOrchestrator.php           # Service: load config, dispatch scrapers, aggregate results
│   ├── PriceCleaner.php                  # Regex-based price extraction
│   ├── ConfigManager.php                 # Read scraper config from DB (not JSON file)
│   ├── DTOs/
│   │   ├── StoreConfig.php               # Typed config value object
│   │   └── ScrapeResult.php              # Structured result per product
│   └── Exceptions/
│       ├── ScrapeFailedException.php
│       └── SelectorNotFoundException.php
├── Console/Commands/
│   └── RunScraperCommand.php             # UPDATED: calls PHP scrapers directly (no exec)
├── Services/
│   ├── ScraperRunnerService.php          # UPDATED: runs scrapers in-process
│   └── ScraperConfigService.php          # REFACTOR: store config in DB, not JSON file
├── Repositories/
│   ├── PriceHistoryRepository.php        # UNCHANGED (already reads from DB)
│   └── ScraperConfigStore.php            # REFACTOR: DB-based config, not JSON
└── Http/Controllers/
    └── ScraperController.php             # FACADE UNCHANGED (same routes, same views)
```

### 4.2 Class Hierarchy

```
ScraperInterface (Contract)
    │
    ▼
BaseScraper (Abstract)
    ├── $config: StoreConfig
    ├── cleanPrice(string $raw): ?float
    ├── saveToDb(int $productId, string $url, ?float $price): bool
    ├── shouldSkip(string $url): bool          // NEW: skip "#" URLs immediately
    └── abstract scrape(): void
        │
        ├── StaticScraper
        │   ├── HTTP client: Laravel Http facade
        │   ├── HTML parser: Symfony DomCrawler
        │   └── Retry: 3 attempts + exponential backoff
        │
        └── DynamicScraper
            ├── Browser: chrome-php/chrome (headless Chromium)
            ├── Page API: waitForSelector → getText
            └── Retry: 3 attempts + exponential backoff
```

### 4.3 Data Flow (Proposed)

```
Admin UI / Scheduler
    │
    ▼
ScraperOrchestrator::run(?string $storeName)
    │
    ├── Loads config from `stores` + `store_product` + `store_scraper_configs` tables
    ├── Filters to store if specified
    ├── For each store:
    │     ├── Instantiates StaticScraper or DynamicScraper
    │     ├── Logs store start via Laravel Log facade (channel: scraper)
    │     ├── Each product:
    │     │     ├── Skip if url === '#' or empty
    │     │     ├── Fetch → Parse → Clean → saveToDb()
    │     │     ├── Log success/failure
    │     │     └── Sleep($delay)
    │     └── Logs store completion
    └── Returns ScrapeResult[] collection
```

### 4.4 Config Storage Migration

**Current**: `scraper/config.json` flat file, synced by `ScraperConfigService`
**Proposed**: Eliminate JSON config entirely. Read directly from `stores`, `store_product`, and a new `store_scraper_configs` table.

**New migration table:**

```sql
CREATE TABLE store_scraper_configs (
    store_id BIGINT UNSIGNED PRIMARY KEY,
    mode ENUM('static', 'dynamic') NOT NULL DEFAULT 'static',
    delay INT NOT NULL DEFAULT 3,
    price_selectors JSON NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'JOD',
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
);
```

### 4.5 Logging Strategy

Use Laravel's native logging with a dedicated `scraper` channel:

```php
// config/logging.php
'scraper' => [
    'driver' => 'stack',
    'channels' => ['single', 'stderr'],
    'path' => storage_path('logs/scraper.log'),
    'level' => 'info',
],
```

- Mirrors Python log format: timestamp, channel, level, message
- Aggregatable via Laravel's log viewer / ELK / CloudWatch
- Optionally: add `daily` rotation channel for production

---

## 5. Recommended Libraries

### 5.1 For HTTP Requests: Laravel HTTP Client

**Replaces**: `requests`

**Why**:
- Built on Guzzle, battle-tested
- Familiar Laravel API: `Http::withHeaders([...])->get($url)->body()`
- Built-in retry support: `Http::retry(3, 2000)->get(...)`
- Built-in timeout, error handling, response validation
- No additional dependency needed — already present in Laravel 11

### 5.2 For HTML Parsing: Symfony DomCrawler + CssSelector

**Replaces**: `beautifulsoup4` + `lxml`

**Why**:
- CSS selector support identical to BeautifulSoup's `select_one()`
- Already a transitive dependency of Laravel (used internally for HTTP testing)
- `$crawler->filter('.product-price')->first()->text()` maps 1:1 to BS4's `soup.select_one('.product-price').get_text()`
- No HTML parsing speed concerns for single-page scraping (not crawling thousands of pages)
- Install explicitly: `composer require symfony/css-selector`

### 5.3 For Dynamic/JS Rendering: chrome-php/chrome

**Replaces**: `playwright`

**Why**:
- PHP-native headless Chrome library using Chrome DevTools Protocol (CDP)
- No Node.js dependency (unlike Playwright, which requires Node)
- Supports `waitForSelector`, `evaluate`, `getText` — matches Playwright's API
- Reuses the same Chromium binary installed via `playwright install chromium` or system Chromium
- Active maintenance, PSR-compliant

**Alternative considered**: `php-webdriver/webdriver` + Selenium Standalone Server — more heavyweight, requires a separate Java/Selenium process. `chrome-php/chrome` is simpler and sufficient for this use case.

**Install**: `composer require chrome-php/chrome`

### 5.4 For MySQL Access: Laravel Eloquent / Query Builder

**Replaces**: `mysql-connector-python`

**Why**:
- The entire application already uses Eloquent
- `PriceHistory::create([...])` vs raw SQL with parameterized queries
- Connection pooling, query logging, and error handling come free
- Zero-risk — same DB, same credentials, same PDO driver
- `DB::transaction(fn() => ...)` for atomic inserts

### 5.5 For SQLite Access: PHP PDO SQLite Extension

**Replaces**: Python `sqlite3` stdlib

**Why**:
- `new \SQLite3('components.sqlite')` for `build_db_index.py` replacement
- `BackfillSpecs` command already reads `components.sqlite` via PHP's SQLite3
- Can also use Laravel's `DB::connection('sqlite')` with a separate config entry
- Built into PHP (ext-sqlite3), no extra install needed

### 5.6 For Logging: Laravel Log Facade

**Replaces**: Python `logging` module

**Why**:
- `Log::channel('scraper')->info('message')` mirrors Python `logger.info('message')`
- Structured logging available via Monolog
- Log rotation, daily files, Slack alerts built-in
- Familiar to Laravel developers
- Already present

### 5.7 For Async/Delay: Scheduler + Sleep

**Replaces**: `asyncio.sleep()` + missing cron

**Why**:
- `sleep($delay)` for politeness delays between product requests
- Laravel Scheduler for cron-based runs (6-hour interval per README recommendation)
- Queue jobs (optional) for non-blocking admin UI runs
- No async needed — crawling is sequential and IO-bound

---

## 6. Migration Roadmap

### Phase 0: Preparation (1 day)

**Objective**: Set up infrastructure without changing any scraper behavior.

| Step | Description | Files | Risks | Dependencies | Validation |
|---|---|---|---|---|---|
| 0.1 | Create `store_scraper_configs` migration | New migration in `database/migrations/` | None — new table only | None | `php artisan migrate` runs clean |
| 0.2 | Create seeder to populate `store_scraper_configs` from `config.json` | `database/seeders/StoreScraperConfigSeeder.php` | None | 0.1 | Seeded data matches current `config.json` store-level settings |
| 0.3 | Install new composer deps: `chrome-php/chrome`, `symfony/css-selector` | `composer.json` | Unused deps (safe) | None | `composer install` succeeds |
| 0.4 | Add `scraper` log channel to `config/logging.php` | `config/logging.php` | Log conflicts (unlikely) | None | `Log::channel('scraper')->info('test')` writes to `storage/logs/scraper.log` |

### Phase 1: Base Layer (2-3 days)

**Objective**: Build abstract base, price cleaner, config management. No scraping occurs yet.

| Step | Description | Files Created/Modified | Risks | Dependencies | Validation |
|---|---|---|---|---|---|
| 1.1 | Create `ScraperInterface` contract | `app/Scraping/Contracts/ScraperInterface.php` | None | 0.3 | PHPUnit: contract defines expected methods |
| 1.2 | Implement `PriceCleaner` | `app/Scraping/PriceCleaner.php` | Regex parity with Python `clean_price()` | 0.4 | PHPUnit: test 20+ price strings from log file; output must match Python exactly |
| 1.3 | Create `StoreConfig` DTO | `app/Scraping/DTOs/StoreConfig.php` | Type safety | None | Unit test: construct from array |
| 1.4 | Create `ScrapeResult` DTO | `app/Scraping/DTOs/ScrapeResult.php` | None | None | Unit test |
| 1.5 | Implement `BaseScraper` abstract class | `app/Scraping/BaseScraper.php` | DB write logic must match `writer.py` exactly | 1.1, 1.2, 1.3, 1.4 | Unit test: `saveToDb()` and `cleanPrice()` produce correct DB state |
| 1.6 | Implement `ConfigManager` | `app/Scraping/ConfigManager.php` | Must produce same store/product mapping as JSON config | 0.2 | Integration test: config output matches JSON entries |
| 1.7 | Create exception classes | `app/Scraping/Exceptions/ScrapeFailedException.php`, `SelectorNotFoundException.php` | None | None | Unit tests |

**Critical validation**: Run `PriceCleaner` against all price strings from `scraper/logs/scraper.log`. Every `clean_price()` call from Python must produce identical float values in PHP.

### Phase 2: Static Scraper (2-3 days)

**Objective**: Replace `StaticScraper` (Midas Computer Center) with PHP. Keep DynamicScraper in Python temporarily.

| Step | Description | Files Created/Modified | Risks | Dependencies | Validation |
|---|---|---|---|---|---|
| 2.1 | Implement `StaticScraper` | `app/Scraping/Scrapers/StaticScraper.php` | Request/parse behavior must match Python `static_scraper.py` | 1.5, 1.6 | Run on Midas store; compare prices with last Python run |
| 2.2 | Add `shouldSkip()` logic to `BaseScraper` | `app/Scraping/BaseScraper.php` | None (new method) | 1.5 | Products with `"#"` URL are skipped immediately |
| 2.3 | Modify `RunScraperCommand` | `app/Console/Commands/RunScraperCommand.php` | Must not break Admin UI | 2.1 | `php artisan scraper:run --store="Midas Computer Center"` works |
| 2.4 | Modify `ScraperRunnerService` | `app/Services/ScraperRunnerService.php` | Must not break `ScraperController` | 2.3 | Admin dashboard button works for Midas only |
| 2.5 | Keep DynamicScraper in Python temporarily | No change to `scraper/scrapers/dynamic_scraper.py` | Dual runtime (temporary) | 2.3 | Full run: static store via PHP, dynamic stores via Python (no regression) |

**Critical validation**: Compare `price_history` rows from Python run vs PHP run for identical Midas products. Prices must match within 0.00. Status values must match.

### Phase 3: Dynamic Scraper (3-4 days)

**Objective**: Replace `DynamicScraper` (3 stores) with PHP. Remove Python dependency.

| Step | Description | Files Created/Modified | Risks | Dependencies | Validation |
|---|---|---|---|---|---|
| 3.1 | Implement `DynamicScraper` with `chrome-php/chrome` | `app/Scraping/Scrapers/DynamicScraper.php` | Chrome process management, memory leaks, zombie processes | 1.5, 1.6, 0.3 | Run on 1 product; verify price matches Python output |
| 3.2 | Implement `BrowserPool` or browser lifecycle management | `app/Scraping/Scrapers/DynamicScraper.php` (internal) | Memory growth over many products | 3.1 | Memory/CPU profiling during full store run; no zombie Chromium after completion |
| 3.3 | Integrate Number One Store | `app/Scraping/Scrapers/DynamicScraper.php` | Different selectors, JS timing variations | 3.2 | Compare 10 consecutive runs vs Python results |
| 3.4 | Integrate City Center | `app/Scraping/Scrapers/DynamicScraper.php` | Same selector concerns | 3.2 | Compare runs vs Python |
| 3.5 | Integrate Oriental Store | `app/Scraping/Scrapers/DynamicScraper.php` | Same selector concerns | 3.2 | Compare runs vs Python |
| 3.6 | Delete Python scraper code | Remove `scraper/scrapers/`, `scraper/db/`, `scraper/scraper.py`, `scraper/requirements.txt` | Nothing calls Python anymore | All 3.x steps pass | `grep -r "python" app/` returns no scraping references |

**Key risk mitigation**: Browser-based scraping is inherently flaky. The PHP implementation must handle:
- Page load timeouts → skip product gracefully
- Selector not found → try all fallbacks before failing
- Browser crash → restart browser, continue with remaining products
- Memory growth → close/reopen browser context every N products (e.g., every 20)

### Phase 4: Orchestration & Scheduling (1-2 days)

**Objective**: Build final orchestrator, add cron scheduling, clean up legacy artifacts.

| Step | Description | Files Created/Modified | Risks | Dependencies | Validation |
|---|---|---|---|---|---|
| 4.1 | Implement `ScraperOrchestrator` | `app/Scraping/ScraperOrchestrator.php` | Aggregated error handling | 2.1, 3.1 | Full 4-store run completes end-to-end |
| 4.2 | Add scheduler entry in `Kernel.php` | `app/Console/Kernel.php` | Cron misconfiguration | 4.1 | `php artisan schedule:run` triggers scraper |
| 4.3 | Refactor `ScraperConfigService` | `app/Services/ScraperConfigService.php` | Remove JSON file sync; use DB-only config | 1.6 | `store_scraper_configs` is the single source of truth |
| 4.4 | Create queue job wrapper (optional) | `app/Jobs/RunScraperJob.php` | Job timeouts for long runs | 4.1 | `php artisan queue:work` processes scraper jobs; admin UI returns instantly |
| 4.5 | Remove `scraper/config.json`, `scraper/.env` | Delete files | No remaining consumers | 4.3 | Files deleted; scraper runs without them |
| 4.6 | Update `README.md` | `scraper/README.md` | None | All above | Instructions reflect PHP-only setup, no Python required |

### Phase 5: Polish & Cleanup (1 day)

| Step | Description | Files |
|---|---|---|
| 5.1 | Skip `"#"` URLs immediately without retry (saves ~2.5 minutes per full run) | `app/Scraping/BaseScraper.php` |
| 5.2 | Add `ScraperLog` model for per-run audit trail (run_id, store, products_total, products_ok, products_failed, duration_seconds) | New migration + model |
| 5.3 | Add admin dashboard chart of scrape history (optional) | `resources/views/admin/scraper/` |
| 5.4 | Configure Laravel throttle on scrape endpoint | `routes/web.php` |
| 5.5 | Remove Python from server deployment | CI/CD, Dockerfile |
| 5.6 | `composer remove` any unused packages | `composer.json` |

---

## 7. Risk Assessment

| Risk | Severity | Probability | Mitigation |
|---|---|---|---|
| **Playwright → chrome-php/chrome parity gap** | High | Medium | Phase 3: Test each dynamic store individually. Fallback: keep Playwright via `symfony/process` as temporary escape hatch. |
| **CSS selector behavior differences** | Medium | Low | Symfony DomCrawler uses same CSS selector spec as BeautifulSoup. Still, test every selector from current `config.json`. |
| **Chrome memory leaks in long runs** | High | Medium | Phase 3.2: Implement BrowserPool with max-age (restart browser every N products). Monitor RSS during test runs. |
| **Regex price cleaning edge cases** | Medium | Low | Phase 1.2: Exhaustive PHPUnit with all price strings from the log file. Add edge cases: Arabic text, empty strings, whitespace-only. |
| **DB write inconsistency during partial failure** | Medium | Medium | Wrap INSERT + UPDATE in a DB transaction per product. Python version writes separately without transactions — a pre-existing bug. |
| **Admin UI timeout during full run** | High | Medium | Phase 4.4: Queue job dispatch. Admin UI returns immediately with "Scraper queued" flash message. |
| **config.json sync race condition** | Low | N/A | Risk disappears when JSON config is eliminated in Phase 4.3. |
| **Python `.env` misalignment** | Low | N/A | Risk disappears when Python DB access is removed. PHP uses same Laravel `.env` values via `config('database.connections.mysql')`. |
| **BackfillSpecs command breakage** | Low | Low | Already reads `components.sqlite` via PHP SQLite3. Does not depend on Python scraper. |
| **Chromium binary not found** | Medium | Low | Use existing Chromium from `playwright install chromium`. Document system Chromium fallback path. |
| **Selector changes on target websites** | Medium | Medium | Pre-existing risk — websites change their markup independently of this migration. No new risk introduced. |

---

## 8. Validation Strategy

### 8.1 Unit Tests (Phase 1)

```php
// PriceCleanerTest.php
test('cleans JOD prices correctly', function (string $raw, ?float $expected) {
    expect(app(PriceCleaner::class)->clean($raw))->toBe($expected);
})->with([
    ['JD 25.99', 25.99],
    ['JOD 389.0', 389.0],
    ['459.0 JOD', 459.0],
    ['', null],
    [null, null],
    ['غير متوفر', null],      // Arabic "unavailable"
    ['12,999.50 JOD', 12999.50],
    ['  99.0  ', 99.0],
    ['JOD 0.00', 0.0],
    ['$299.99', 299.99],
]);

// BaseScraperTest.php
test('saveToDb creates price_history and updates store_product', function () {
    // ... creates DB records, verifies both tables
});

test('shouldSkip returns true for placeholder URLs', function () {
    expect($scraper->shouldSkip('#'))->toBeTrue();
    expect($scraper->shouldSkip(''))->toBeTrue();
    expect($scraper->shouldSkip('https://example.com/product'))->toBeFalse();
});
```

### 8.2 Integration Tests (Phase 2-3)

```php
// StaticScraperTest.php
test('scrapes Midas store prices matching Python output', function () {
    // Run PHP StaticScraper on Midas store
    // Query price_history for recent PHP-scraped entries
    // Compare each price with known-good Python results from scraper.log
});

// DynamicScraperTest.php
test('scrapes Oriental Store prices matching Python output', function () {
    // Same pattern as above for each dynamic store
});
```

### 8.3 Regression Test (Phase 3)

After each store migration step, run both Python and PHP scrapers against the same store and compare:
- Price values (must be identical)
- Status values (ok/failed must match)
- Number of products processed
- `store_product.product_price` updated correctly

### 8.4 Acceptance Criteria

| # | Criterion | How Verified |
|---|---|---|
| 1 | All 4 stores scrape successfully via PHP | Full run: `php artisan scraper:run` |
| 2 | Prices match Python output (±0.00) | Manual comparison of `price_history` rows |
| 3 | Failed products produce `status='failed'` rows | Check DB for failed entries after run |
| 4 | `store_product.product_price` updated after scrape | Query DB after run |
| 5 | No Chromium zombie processes after run | `ps aux \| grep chrome` returns 0 |
| 6 | Admin dashboard shows correct stats | Visit `/dashboard/scraper` after run |
| 7 | Scheduler fires correctly | `php artisan schedule:run` triggers scraper |
| 8 | `"#"` URLs skipped without retry | Check log: 0 "Attempt X failed for #" entries |
| 9 | Python no longer required | `which python` not referenced anywhere in scraper path |
| 10 | `BackfillSpecs` command still works | `php artisan specs:backfill` runs without error |

### 8.5 Rollback Plan

If any Phase fails:

1. **Phase 2**: Keep both `StaticScraper.php` and Python `static_scraper.py`. The command can dispatch to either.
2. **Phase 3**: Keep old `RunPythonScraperCommand` as `app/Console/Commands/RunPythonScraperCommand.php` (rename the new PHP-native one differently until validated).
3. **config.json**: Do **not** delete until Phase 4.5, after full 4-store validation passes.
4. **Python files**: Do **not** delete until Phase 3.6 (after all dynamic stores are validated).

---

## 9. Summary: Before vs After

| Metric | Current (Python) | Proposed (PHP) |
|---|---|---|
| **Runtimes required** | Python 3.13 + PHP 8.x | PHP 8.x only |
| **External dependencies** | 6 pip packages + Chromium | 1 composer package + Chromium |
| **Config storage** | JSON file synced from DB | DB only (single source of truth) |
| **Process model** | `exec()` subprocess | In-process PHP or queued job |
| **Retry on dead URLs** | 3x per `"#"` URL (~2.5 min wasted) | Skip immediately |
| **Scheduling** | None (manual only) | Laravel Scheduler (every 6 hours) |
| **Error reporting** | stdout string capture | Structured exceptions + Laravel Log |
| **DB atomicity** | None (partial writes possible) | Per-product DB transactions |
| **Admin UI blocking** | Blocks during full run | Non-blocking via queued jobs |
| **Codebase language count** | 2 (Python + PHP) | 1 (PHP) |
| **Deployment complexity** | Python runtime + pip + npm (Playwright) | PHP runtime + Chromium binary only |
