# PC-Tech — Migration Plan to a 3-Tier Architecture

> Status: PLAN ONLY. No production code is modified by this document.
> Primary references: `docs/diagrams.md` (DB Schema, Logical DFD, Physical DFD, Sequence Diagram, Use Case Diagram) and `docs/testing-strategy.md`, verified line-by-line against the codebase.

## 0. Baseline, Ground Rules, and Hard Constraints

**Test baseline (verified):** `143 passed (364 assertions), 2 deprecation notices (PHP 8.5 PDO constant, environment-only), ~4s`.
Run command: `php artisan test` (requires PHP >= 8.2; the system `php` is 8.1 — use the Homebrew PHP 8.3 binary). Test DB is MySQL `pc_tech_test`, Scout driver `collection`, transactions per test.

**Ground rules honored throughout:**

- No rewrite. Only additive classes (repositories, services) + rewiring of existing controllers.
- External behavior is pinned: routes, URLs, view names, view-data keys, JSON shapes, redirects, flash message text, status codes, pagination sizes, sort orders — all unchanged.
- **Known defects are preserved, not fixed** (documented in `testing-strategy.md` as G1–G15): the admin-only contact POST (G1), the 500-instead-of-404 on `/single-page/{bad-id}` (G4), the IDORs (G5, G6), the broken `ProductImageController::destroy` (G9), etc. Migration is not a bug-fix exercise; fixes are a separate, later decision.
- Every step ends with the full suite green. Steps are ordered so each can be merged independently.

**Hard constraints discovered in the test suite:**

1. `tests/Unit/ProductSpecMappingTest` (19 tests) invokes `detectComponentType` and `mapSpecs` **via reflection directly on `ProductController`**. When this logic moves to the Business Logic Layer, `ProductController` must keep thin private delegate methods with those exact names (or the test target changes in the same step). The plan chooses delegates → zero test edits.
2. `AccessMatrixTest` pins status codes for guest/user/admin/super-admin across the whole `/dashboard` surface and all `restore-*` routes — any authorization drift is caught immediately.
3. Feature tests assert exact view-data (`cheapest_price`, `priceHistory`, `priceHistoryChart`), JSON keys, and DB rows — query semantics must be moved verbatim.

---

## 1. Assessment of the Current Architecture

The application is a Laravel 10 monolith + a Python price-scraper microservice. Structurally it is a **"fat controller" 1.5-tier system**:

- **Presentation and Business and Data Access are fused inside controllers.** Every controller (except `HomeController`) queries Eloquent models or the `DB` facade directly, and most also contain business rules.
- **One anemic service exists** (`BuildCompatibilityService`) — proof the team already feels the pain, but it covers only the PC Builder module.
- **Eloquent models are used as the data access layer from everywhere**, including raw-SQL correlated subqueries (`MIN(product_price)`) copy-pasted into four different controller methods.
- **Duplicated logic across controllers:** image upload handling is copy-pasted into 5 controllers (`Category`, `Store`, `User`, `UserSide`, `ProductImage`); the cheapest-price subquery appears 4x; the price-history join appears 2x (`UserSideController::singlePage`, `ProductController::show`); password-change logic appears 2x (`UserSideController`, `UserController`); restore logic appears 6x.
- **A controller reaches past the database entirely:** `ProductController::fetchSpecs()` opens a raw PDO connection to `database/components.sqlite`, and `syncScraperConfig()` rewrites `scraper/config.json` on disk — external integration inside a presentation class.
- **The Python scraper is internally healthier** than the PHP app: `scraper.py` (entry) -> `StaticScraper`/`DynamicScraper` (fetch/parse rules) -> `db/writer.py` (DB writes). It already approximates 3 tiers and needs no restructuring — only its PHP-side contract (`config.json`) needs a proper owner.

**Consequences observed:** the testing-strategy doc had to specify reflection-based tests to reach logic buried in a controller; price correctness (R2, "Critical") lives in duplicated raw SQL; the PHP<->Python contract (R3, "Critical") is maintained by a private controller method.

### 1.1 Current dependency graph

```
                        +--------------------------- PRESENTATION ---------------------------+
 routes/web.php ------► 20 Controllers (App\Http\Controllers)
                        |   |  |  |   |   |   |
        +---------------+   |  |  |   |   |   +------------------------------+
        v                   v  v  v   v   v                                  v
  Eloquent Models --> MySQL |  DB::raw subqueries      PDO --> components.sqlite (fetchSpecs)
  (Build, BuildPart, pc_tech|  (cheapest price x4)     File --> public/uploads/* (x5 controllers)
   Category, Contact,       |  file_put_contents ----> scraper/config.json (ProductController)
   Faqs, Feedback,          |  Artisan::call --------> RunScraperCommand --> exec("python scraper.py")
   PriceHistory,            v
   Product, ProductImage, Blade views (56 templates)  <---- presentation output
   Store, User)
        | Scout Searchable (Product, Category, Contact, Faqs, Store, User)
        | model-boot cascades: Category->products, User->contacts
        v
 +---------------- PYTHON SCRAPER (cron 0 */6 * * *) ----------------+
 | scraper.py -> StaticScraper / DynamicScraper -> db/writer.py      |
 |      reads config.json (rewritten by ProductController!)          |
 |      writer.py -> INSERT price_history + UPDATE store_product ----+--> MySQL pc_tech
 +-------------------------------------------------------------------+

Authorization: middleware AdminOrSuperAdmin / Superadmin (all admin routes), BuildPolicy (build delete)
Cross-cutting: 11 middleware, 5 providers, Exception handler — framework bootstrap, unchanged.
```

Every arrow from a controller down to MySQL/PDO/File/config.json is a layer violation to be removed. Nothing flows through a business layer today except `BuildController -> BuildCompatibilityService`.

---

## 2. Diagram-Driven Analysis

### 2.1 Database Schema Analysis -> Data Access Layer ownership

| Entity (schema) | Relationships | CRUD responsibility today | Target DAL class |
|---|---|---|---|
| `users` | 1-N contacts, 1-N feedback, N-M favorite, 1-N builds; soft-deletes; boot cascade -> contacts | Register, login reads, account update, admin CRUD, restore | `UserRepository` |
| `categories` | 1-N products; soft-deletes; boot cascade -> products | Admin CRUD + search/sort, public navbar reads, restore | `CategoryRepository` |
| `products` | N-1 category, 1-N images, N-M stores, 1-N feedback, N-M builds; soft-deletes | Public browse/compare reads, admin CRUD, Scout search, cheapest-price aggregate, restore | `ProductRepository` |
| `product_images` | N-1 product; soft-deletes; `$fillable=[]` (insert-only) | Admin upload/delete | `ProductImageRepository` |
| `stores` | N-M products; soft-deletes | Admin CRUD, restore | `StoreRepository` |
| `store_product` (pivot) | N-M products<->stores, 1-N price_history | Attach/updateExistingPivot on product save; MIN-price subquery; scraper config sync; scraper UPDATE side-effect | `StoreProductRepository` |
| `price_history` | N-1 store_product (`sp_id`) | Scraper INSERT (Python), SEQ-2 join reads (PHP), scraper dashboard stats | `PriceHistoryRepository` |
| `feedback` | N-1 product, N-1 user; **hard delete only** | User submit/update/delete, dashboard aggregates | `FeedbackRepository` |
| `favorite` (pivot w/ id) | N-M users<->products | Toggle/detach/list | `FavoriteRepository` |
| `contacts` | N-1 user; soft-deletes | Public form insert (currently admin-only — G1), admin inbox, restore | `ContactRepository` |
| `faqs` | soft-deletes | Admin CRUD, public list, restore | `FaqRepository` |
| `builds`, `build_parts` *(undocumented in schema diagram, gap G2)* | builds N-1 user, N-M products via build_parts; soft-delete on builds | Builder save/list/delete; total-price aggregation | `BuildRepository` |
| `components.sqlite` *(external store)* | none | `fetchSpecs` exact/LIKE/fallback reads via PDO | `ComponentSpecReader` |
| `scraper/config.json` *(external store)* | written by PHP, read by Python | `syncScraperConfig` rewrite after product changes | `ScraperConfigStore` |

**Data-access patterns to encapsulate:** soft-delete scopes + `withTrashed/onlyTrashed/restore`; the two model-boot cascades (stay in models — moving them risks behavior change); pivot attach/detach/updateExistingPivot; correlated `MIN(product_price)` subquery; the `price_history -> store_product -> stores` join with `status='ok'`; Scout `search()` calls (wrapped inside repositories so `SCOUT_DRIVER=collection` keeps tests green); pagination with `withQueryString`.

### 2.2 Logical DFD Analysis -> Business Logic Layer ownership

| Logical process | Business rules / workflows identified | Target BLL service |
|---|---|---|
| **P1 Manage authentication** (D1 user accounts) | Register with forced `role='user'`, hashed password; login/logout; password reset (framework) | `UserManagementService` (registration path; Laravel UI traits stay in PL) |
| **P2 Browse & compare** (D2, D4) | Landing/category/single-page assembly; brand counts per category; Scout search branch; **cheapest-price selection per product**; latest-price-per-store; chart series grouping; `failed` rows excluded | `CatalogService` |
| **P3 Manage favourites** (D3) | Toggle-if-exists-else-attach rule; per-user listing | `FavoriteService` |
| **P4 Submit feedback** (D3) | Rate 1–5 rule, create/update/delete workflow, redirect flows | `FeedbackService` |
| **P5 Manage the catalogue** (D2) | Admin CRUD workflows for products/categories/stores/images/faqs/users/contacts; spec key=>value JSON encoding; store pivot sync; image upload workflow (naming, `uploads/<type>/` placement); dashboard aggregate stats | `ProductService`, `CategoryService`, `StoreService`, `ProductImageService`, `FaqService`, `UserManagementService`, `ContactService`, `ImageUploadService`, `DashboardService` |
| **P6 Collect prices** (D4) | Retry x3, parse/clean price, ok/failed row contract, store_product price sync *(Python — already internal BLL)*; PHP side: **config.json contract sync**, manual trigger orchestration, spec fetch/map for the create form | `ScraperConfigService`, `ScraperRunnerService`, `SpecMappingService` (PHP); scrapers stay as-is (Python) |
| *(Undocumented, gap G2)* PC Builder | 4 compatibility rules; build save transaction with `category_name` snapshot and total = sum of cheapest prices; owner-only delete | `BuildCompatibilityService` (exists), new `BuilderService` |
| Super-admin restore (P-DFD p13) | `deleted_at = NULL` workflow x6 entities | `RestoreService` |

Validation stays in controllers (`$request->validate`) — in Laravel that is a presentation concern; no FormRequest extraction (kept minimal, optional later).

### 2.3 Physical DFD Analysis -> violations found

| P-DFD process | Current implementation | Violation |
|---|---|---|
| p1 `UserSideController` public pages | 4 direct SELECTs (products, categories, faqs, price_history) + raw MIN subquery + chart shaping | PL->DB direct; BL in PL |
| p2 `ContactController::store` | Direct INSERT (and placed behind admin middleware — gap G1, **preserved**) | PL->DB direct |
| p3 `Auth::routes()` | Laravel UI; `RegisterController::create` inserts user | Minor: PL->DB (framework-tolerated; still rewired) |
| p4 `FavoriteController` | Direct pivot attach/detach; toggle rule in controller | PL->DB + BL in PL |
| p5 `FeedbackController` | Direct INSERT/UPDATE/DELETE; mass-assignment via `$request->all()` (G6, **preserved**) | PL->DB + BL in PL |
| p6 `UserSideController` account | Direct UPDATE users; password-check business rule in controller | PL->DB + BL in PL |
| p7_dash `DashboardController` | 4 aggregate SELECT groups + chart dataset shaping in controller | PL->DB + BL in PL |
| p8 `ProductController` | Direct CRUD + pivot sync + spec JSON encoding + **raw PDO to SQLite** + **config.json rewrite** | Worst offender: PL->DB, PL->external stores, BL in PL |
| p9_img `ProductImageController` | File moves + direct insert/delete (destroy broken per G9, **preserved**) | PL->DB + filesystem in PL |
| p9_cat / p7_store / p9_faq / p8_user / p10 | Direct CRUD + duplicated upload blocks | PL->DB + BL in PL |
| p11 `ScraperController` | `Artisan::call` + flash mapping in controller (P-DFD says `shell_exec`; actual is equivalent) | PL contains orchestration |
| p13 Restore x6 controllers | Direct `withTrashed()->find()->restore()` x6 | PL->DB, duplicated |
| p14–p17 Python pipeline | config load -> static/dynamic scrape -> `writer.py` INSERT | **Internally acceptable** (entry / rules / DB writer ~ 3 tiers); only its config contract is owned by the wrong PHP class |

### 2.4 Sequence Diagram Analysis -> target distribution

**SEQ-1 (scraper run).** Current: cron -> scraper.py -> config -> GET URL -> parse -> retry -> `INSERT price_history` -> log. Tight coupling: the *input contract* (`config.json`) is regenerated by `ProductController` (PL). Target: the pipeline itself is untouched (Python already separates entry/rules/writer). The PHP trigger becomes: `ScraperController` (PL) -> `ScraperRunnerService` (BLL, owns Artisan delegation + flash mapping decision data) -> `RunScraperCommand` (PL-cli) -> python -> `writer.py` (Python DAL). The config contract becomes: `ProductService` (BLL) -> `ScraperConfigService` (BLL, decides content) -> `ScraperConfigStore` (DAL, owns file format/write) — byte-identical output.

**SEQ-2 (product page).** Current: `UserSideController::singlePage` runs SELECT product, then the price_history join, groups latest-per-store, builds chart series, renders Blade — all in one method. Target: route -> `UserSideController::singlePage` (PL: fetch id, delegate, render same view with same keys) -> `CatalogService::productPage(id)` (BLL: orchestration, latest-per-store grouping, chart shaping, `status='ok'` rule) -> `ProductRepository` + `PriceHistoryRepository` (DAL: the two queries, verbatim SQL semantics) -> MySQL. The `find($id)`-then-dereference 500 behavior (G4) is preserved.

**Coupling findings this fixes:** business logic in controllers (all of section 2.3), database access from presentation (all), missing service boundaries (no owner for the PHP<->Python contract, no owner for price comparison rules, 6 duplicated restore flows).

### 2.5 Use Case Analysis -> business operations and capability preservation

| Use case | Business operation (target BLL method group) | Preservation verified by |
|---|---|---|
| g1 Browse website/products | `CatalogService`: landing, category browse + brand counts, static pages | `LandingTest`, `CategoryPageTest`, `StaticPagesTest`, AccessMatrix SEC-F-04 |
| g2 Compare store prices | `CatalogService::productPage` (latest-per-store, chart, ok-only) | `SinglePageTest` |
| g3 Login / Register | `UserManagementService::register`; Laravel UI auth unchanged | `AuthenticationTest` (20 tests) |
| g4 Submit Contact Form | `ContactService::submit` (placement unchanged per G1) | AccessMatrix (current admin-only matrix pinned) |
| lu1 Manage Favourites | `FavoriteService`: toggle, remove, list | `FavoriteTest` (6) |
| lu2 Rate Products / lu3 Submit Feedback | `FeedbackService`: create/update/delete | `FeedbackTest` (7) |
| lu4 Manage Account | `AccountService`: profile update, password change | `AccountTest` (9) |
| lu5 All guest capabilities | (inheritance) | SEC-F-04 run as user |
| a1 Manage products/categories/stores/users | `ProductService`, `CategoryService`, `StoreService`, `UserManagementService` | AccessMatrix SEC-F-01 + added characterization tests (see section 5 note) |
| a2 Trigger Scraper | `ScraperRunnerService::run` | AccessMatrix scraper routes |
| a3 View Contact Msgs | `ContactService` inbox ops | AccessMatrix contacts routes |
| a4 View Feedback | `DashboardService` aggregates | AccessMatrix dashboard |
| a5 All user capabilities | (inheritance) | SEC-F-01/04 as admin |
| sa1 Manage Admin | `UserManagementService` admin CRUD | AccessMatrix users routes |
| sa2 Restore Deleted records | `RestoreService` x6 entities | AccessMatrix SEC-F-02 restore matrix |
| sa3 All admin capabilities | (inheritance) | Full AccessMatrix |

Actor hierarchy (Guest < User < Admin < Sup Admin) remains enforced by the unchanged middleware; no capability moves between actors.

---

## 3. Responsibilities of the Three Layers

### 3.1 Presentation Layer (PL)
**Owns:** HTTP routing (`routes/web.php`, `routes/api.php`), controllers, Blade views, request validation (`$request->validate`), auth middleware (`AdminOrSuperAdmin`, `Superadmin`, `Authenticate`, ...), policies (`BuildPolicy`), redirects/flash, JSON shaping of service results, the CLI command class `RunScraperCommand`, and Laravel UI auth scaffolding.
**Must not:** reference Eloquent models, `DB`, `PDO`, `File`, Scout, or the filesystem; contain business rules or query construction.
**Knows about:** BLL services only (constructor-injected).

### 3.2 Business Logic Layer (BLL)
**Owns:** one service per Logical-DFD process (section 2.2): use-case orchestration, business rules (favorite toggle rule, compatibility rules, rate bounds interpretation, spec mapping, latest-per-store selection, chart shaping, scraper config contract content, build-save transaction), and the image-upload workflow (naming/placement rules — kept in BLL to avoid introducing a fourth "infrastructure" layer; only DB persistence is pushed down).
**Must not:** reference `Request`/`Response`/sessions/views/route names; write SQL or know table/column names beyond what repositories expose.
**Knows about:** DAL repositories only. Returns plain arrays / model collections / simple result data.

### 3.3 Data Access Layer (DAL)
**Owns:** Eloquent models (mapping, relations, soft-delete scopes, model-boot cascades — unchanged), new concrete **repository classes** (`app/Repositories`, one per schema entity per section 2.1 — **no interfaces**, the DBMS is not going to change; simplicity over ceremony), all query construction including raw aggregates, Scout search calls, pivot operations, restore queries, plus the two non-MySQL stores: `ComponentSpecReader` (SQLite/PDO) and `ScraperConfigStore` (config.json file). On the Python side, `db/connection.py` + `db/writer.py` are already this layer.
**Must not:** contain business decisions (no "if exists then detach" rules, no price selection policy, no flash concepts).
**Knows about:** nothing above it.

**Dependency rule:** PL -> BLL -> DAL, strictly downward. Eloquent models may only be *named* inside the DAL; controllers and services receive data through repositories/services respectively. (Pragmatic Laravel note: models returned upward as data carriers are acceptable; constructing queries on them outside the DAL is not.)

---

## 4. Target 3-Tier Architecture

```
+---------------------------- PRESENTATION ----------------------------+
| routes/web.php (unchanged)                                           |
| app/Http/Controllers/*        — thin: validate -> call service -> view|
| app/Http/Controllers/Auth/*   — Laravel UI, register() delegates     |
| app/Http/Middleware/*, app/Policies/BuildPolicy — authorization      |
| app/Console/Commands/RunScraperCommand — CLI adapter (exec python)   |
| resources/views/* (unchanged, same view names & data keys)           |
+-------------------------------+--------------------------------------+
                                 v
+---------------------------- BUSINESS LOGIC --------------------------+
| app/Services/                                                        |
|  CatalogService (P2/SEQ-2)        AccountService (UC-lu4)            |
|  FavoriteService (P3)             FeedbackService (P4)               |
|  ProductService (P5)              CategoryService, StoreService      |
|  ProductImageService              FaqService, ContactService         |
|  UserManagementService (P1/P5)    DashboardService (p7_dash)         |
|  BuilderService + BuildCompatibilityService (exists)                 |
|  SpecMappingService (P6 helper)   ScraperConfigService (P6 contract) |
|  ScraperRunnerService (P6 trigger) RestoreService (p13)              |
|  ImageUploadService (shared upload workflow)                         |
+-------------------------------+--------------------------------------+
                                 v
+---------------------------- DATA ACCESS -----------------------------+
| app/Models/* (unchanged: relations, soft deletes, boot cascades)     |
| app/Repositories/                                                    |
|  UserRepository CategoryRepository ProductRepository                 |
|  ProductImageRepository StoreRepository StoreProductRepository       |
|  PriceHistoryRepository FeedbackRepository FavoriteRepository        |
|  ContactRepository FaqRepository BuildRepository                     |
|  ComponentSpecReader (SQLite PDO)   ScraperConfigStore (config.json) |
| -- Python side (unchanged): scrapers/* (rules) -> db/writer.py (DAL)-|
+-------------------------------+--------------------------------------+
                                 v
              MySQL pc_tech · components.sqlite · config.json
```

Target dependency graph per SEQ-2 example: `Browser -> route -> UserSideController -> CatalogService -> ProductRepository / PriceHistoryRepository -> Product / PriceHistory -> MySQL`, and results flow back up the same chain. No diagonal arrows remain.

---

## 5. Mapping of Existing Components to the New Layers

### 5.1 Controllers (Presentation — all remain, all become thin)

| Class | Current responsibility | Target layer | Reason for moving | Required dependency changes |
|---|---|---|---|---|
| `UserSideController` | Public pages (landing, category, singlePage, about, contact, faqs) + account mgmt; runs 6 query groups, chart shaping, password rule | PL (thin) | Drop direct DB/BL; page assembly is BLL, queries are DAL | Depends on `CatalogService`, `AccountService` instead of `Category/Product/Faqs/Feedback/PriceHistory/User` models + `DB` + `Hash` |
| `BuildController` | Builder pages/JSON; total-price calc, save transaction | PL (thin) | Transaction + totals are BLL; queries are DAL | Depends on `BuilderService` + existing `BuildCompatibilityService`; drops `Build/BuildPart/Category/Product` models, `DB` |
| `CategoryController` | Admin category CRUD + upload + restore | PL (thin) | CRUD workflow + upload rules are BLL; queries DAL | Depends on `CategoryService`; drops `Category` model |
| `ContactController` | Admin inbox + store (admin-only POST, G1) | PL (thin) | Same | Depends on `ContactService`; drops `Contact` model |
| `DashboardController` | 4 aggregate query groups + chart shaping | PL (thin) | Aggregates are DAL, shaping is BLL | Depends on `DashboardService`; drops 5 models + `DB` |
| `FaqsController` | Admin FAQ CRUD + restore | PL (thin) | Same pattern | Depends on `FaqService`; drops `Faqs` model |
| `FavoriteController` | Toggle rule + pivot ops + list | PL (thin) | Toggle rule is BLL; pivot is DAL | Depends on `FavoriteService`; drops `Product/User` |
| `FeedbackController` | CRUD + `$request->all()` mass assignment (G6 preserved) | PL (thin) | Workflow is BLL | Depends on `FeedbackService`; drops `Feedback/Product/User` |
| `HomeController` | Returns `home` view | PL | Already correct | None |
| `ProductController` | Admin product CRUD, pivot sync, spec JSON encode, **fetchSpecs (PDO->SQLite, fuzzy match, spec mapping)**, **syncScraperConfig (file rewrite)**, restore | PL (thin) | Worst violator; four responsibilities extracted | Depends on `ProductService`, `SpecMappingService`; keeps private delegate methods `detectComponentType`/`mapSpecs` (reflection-test constraint); drops models/`DB`/PDO/file access |
| `ProductImageController` | Multi-file upload + insert; broken destroy (G9 preserved) | PL (thin) | Upload workflow is BLL; queries DAL | Depends on `ProductImageService`; drops models + `File` |
| `ScraperController` | Stats read + `Artisan::call` trigger | PL (thin) | Orchestration is BLL; stats query is DAL | Depends on `ScraperRunnerService`; drops `PriceHistory`/`Artisan` |
| `StoreController` | Admin store CRUD + upload + restore | PL (thin) | Same pattern | Depends on `StoreService`; drops `Store` |
| `UserController` | Admin user CRUD, admin profile, admin password | PL (thin) | Same pattern + password rule | Depends on `UserManagementService`; drops `User`/`Hash` |
| `Auth\LoginController` | Laravel UI login + role-based redirect | PL | Framework-tolerated; redirect stays | None |
| `Auth\RegisterController` | Validator + `create()` inserts user | PL (thin) | User creation workflow is BLL | `create()` delegates to `UserManagementService`; keeps validator |
| `Auth\ForgotPasswordController`, `Auth\ResetPasswordController`, `Auth\ConfirmPasswordController`, `Auth\VerificationController` | Stock Laravel UI | PL | Framework constraint (Laravel UI requires these in PL) | None |
| `Controller` (base) | Traits only | PL | Framework base | None |

### 5.2 Models (Data Access — all remain, unchanged code)

| Class | Current responsibility | Target layer | Reason | Dependency changes |
|---|---|---|---|---|
| `User`, `Category`, `Product`, `ProductImage`, `Store`, `Contact`, `Faqs`, `Feedback`, `PriceHistory`, `Build`, `BuildPart` | Eloquent mapping, relations, soft deletes, Scout `toSearchableArray`, boot cascades (User->contacts, Category->products) | DAL | They *are* the ORM of the DAL | Stop being referenced from controllers/services directly; used only by repositories. **No code change.** `PriceHistory`'s broken `storeProduct()` relation (G3, missing `StoreProduct` model) is left untouched — documented defect, out of migration scope |

### 5.3 Services, Policy, Middleware, Console, Framework

| Class | Current responsibility | Target layer | Reason | Dependency changes |
|---|---|---|---|---|
| `Services\BuildCompatibilityService` | 4 compatibility rules; queries `Product` directly | BLL | Already correctly placed | Query moved into `ProductRepository::findKeyedByCategory()` — service receives products (minor, Step 15) |
| `Policies\BuildPolicy` | Owner-only build delete | PL | Authorization gate, bound to HTTP `authorize()` | None; `BuildPolicyTest` unaffected |
| `Middleware\AdminOrSuperAdmin`, `Superadmin`, `Authenticate`, `RedirectIfAuthenticated`, `EncryptCookies`, `VerifyCsrfToken`, `TrimStrings`, `TrustHosts`, `TrustProxies`, `ValidateSignature`, `PreventRequestsDuringMaintenance` | HTTP gatekeeping/pipeline | PL | Framework-constrained placement | None; both middleware unit suites unaffected |
| `Console\Commands\RunScraperCommand` | Builds + `exec()` python command, exit-code passthrough | PL (CLI adapter) | Process invocation is presentation-of-CLI; escapeshellarg behavior pinned by design | None (called via Artisan from BLL) |
| `Console\Kernel`, `Exceptions\Handler`, `Providers\*` (x5) | Framework bootstrap | Framework (outside tiers) | Required by Laravel | None |

### 5.4 Python scraper (separate process — no restructuring)

| File | Role | 3-tier reading | Change |
|---|---|---|---|
| `scraper/scraper.py` | CLI entry, config load, store iteration, per-store isolation | its PL | None |
| `scrapers/base.py`, `static_scraper.py`, `dynamic_scraper.py` | clean_price, selector fallback, retry x3 + backoff, ok/failed contract | its BLL | None |
| `db/connection.py`, `db/writer.py` | sp_id resolution, INSERT price_history, conditional store_product sync | its DAL | None |

### 5.5 New classes introduced (additive only)

- **DAL (`app/Repositories`):** the 11 entity repositories + `ComponentSpecReader` + `ScraperConfigStore` (section 2.1). Each method corresponds 1:1 to a query that exists today in a controller (verbatim semantics: same joins, subqueries, sort defaults, paginate sizes, `withQueryString`).
- **BLL (`app/Services`):** the 15 services in section 4 (section 2.2). `ImageUploadService` is shared by Category/Store/User/Account/ProductImage services to eliminate the 5x duplication.

**Coverage note (honesty requirement):** admin *write* endpoints (store/update/destroy for products, categories, stores, users, images, scraper-run) currently have **no behavior-level feature tests** — only `AccessMatrixTest` status-code pins. Each step touching those flows therefore includes adding *characterization tests first* (test-only additions that pin current behavior: DB state, flash text, redirects), so the rewire is genuinely protected. This does not modify production code.

---

## 6. Incremental Migration Roadmap

Order: pilot a trivial module to establish the pattern, then proceed by ascending risk. Every step ends green. **Verification protocol for every step:** `php artisan test` (full suite, must equal the 143-test green baseline plus any newly added characterization tests) + the step-specific classes named below. Diagram references trace each step back to `docs/diagrams.md`.

---

**Step 0 — Lock the baseline (no production changes)**
- *Files:* none (runbook only).
- *Goal:* Record the green baseline (143 passed) and the PHP>=8.2 runner; circulate the behavior-pinning rule (defects G1–G15 are preserved; Architecture Decision: models' boot cascades stay in models; validation stays in controllers).
- *Risks:* none.
- *Required tests:* full suite green.

**Step 1 — Pilot slice: FAQs admin (pattern-setter)**
- *Files:* `app/Http/Controllers/FaqsController.php`; new `app/Repositories/FaqRepository.php`, `app/Services/FaqService.php`.
- *Goal:* Establish the Repository + Service + thin-controller pattern on the simplest CRUD (P-DFD p9_faq, L-DFD P5): all query/Scout calls -> repository; create/update/delete/restore workflow -> service; controller keeps validation, redirects, flash text verbatim.
- *Diagrams:* P-DFD admin p9_faq -> D6 faqs; p13 (restore-f, service-side only for now).
- *Risks:* low; Scout search semantics (`Faqs::search`) must move intact into the repository (driver `collection` in tests).
- *Required tests:* full suite; focus `AccessMatrixTest` (faqs index, restore-f matrix), `StaticPagesTest::test_faqs_page_returns_200` (public page untouched this step).

**Step 2 — ImageUploadService + Categories admin**
- *Files:* `CategoryController.php`; new `ImageUploadService.php`, `CategoryRepository.php`, `CategoryService.php`.
- *Goal:* Move the upload block (first of 5 copies) into the shared `ImageUploadService`; move CRUD + search + restore flow (P-DFD p9_cat, L-DFD P5); keep the model-boot cascade (Category->products) in the model.
- *Diagrams:* P-DFD p9_cat -> D3 categories; DB-schema cascade `categories -> products`.
- *Risks:* upload path/naming (`uploads/category/`, `time().ext`) must be byte-identical; cascade timing unchanged.
- *Required tests:* full suite; add characterization tests for category store/update/destroy (coverage gap) before rewiring; `CategoryPageTest` guards public reads (still direct, untouched).

**Step 3 — Stores admin**
- *Files:* `StoreController.php`; new `StoreRepository.php`, `StoreService.php`. Reuses `ImageUploadService`.
- *Goal:* Same pattern (P-DFD p7_store -> D4 stores). Note: store soft-delete leaves `store_product` rows (pinned behavior).
- *Risks:* low; nullable-image asymmetry vs. categories must remain.
- *Required tests:* full suite + added store characterization tests; `LandingTest`/`SinglePageTest` guard the public `stores` relation reads (untouched).

**Step 4 — Contacts admin inbox**
- *Files:* `ContactController.php`; new `ContactRepository.php`, `ContactService.php`.
- *Goal:* Move inbox list/search/show/delete/restore + the admin-only POST store (P-DFD p10 -> D7 contacts; placement pinned per G1 — the route stays exactly where it is).
- *Risks:* Scout `Contact::search` semantics; validation/flash text verbatim.
- *Required tests:* full suite; `AccessMatrixTest` contact routes; `StaticPagesTest::test_contact_us_page_returns_200` (public GET untouched).

**Step 5 — Users admin & admin profile**
- *Files:* `UserController.php`; new `UserRepository.php`, `UserManagementService.php`.
- *Goal:* Move CRUD (search/role filter/6 sorts), admin profile update, admin password rule (Hash::check -> error bag), restore flow (P-DFD p8_user -> D5 users; UC-sa1). Role-forcing and the G8 escalation behavior are preserved verbatim.
- *Risks:* password-change rule duplicated later with Step 6 — service is designed so both callers share it; boot cascade User->contacts stays in model.
- *Required tests:* full suite + added user-CRUD characterization tests; `AccessMatrixTest` users + restore-u matrix.

**Step 6 — User account (auth user)**
- *Files:* `UserSideController.php` (methods `account`, `updateAccount`, `updatePassword` only); new `AccountService.php`; reuse `UserRepository`, `ImageUploadService`.
- *Goal:* Extract account workflows (P-DFD p6 -> D6 users; UC-lu4) including the password rule; IDOR G5 preserved exactly (no ownership check added).
- *Risks:* `AccountTest` asserts exact error keys (`current_password`) and flash keys (`password_success`) — move logic verbatim.
- *Required tests:* full suite; focus `AccountTest` (9), `AuthenticationTest` guest-redirect cases.

**Step 7 — Registration path**
- *Files:* `Auth/RegisterController.php` only; reuse `UserManagementService`.
- *Goal:* `create()` delegates to the service (L-DFD P1 -> D1): forced `role='user'`, `Hash::make`. Validator stays in the controller (PL). Login/reset/verify controllers untouched (framework).
- *Risks:* low; `AuthenticationTest` pins role + hashing + validation matrix.
- *Required tests:* full suite; focus `AuthenticationTest` (20).

**Step 8 — Favorites**
- *Files:* `FavoriteController.php`; new `FavoriteRepository.php`, `FavoriteService.php`.
- *Goal:* Move toggle rule (exists->detach / else->attach) and listing with images (L-DFD P3 -> D3; P-DFD p4; UC-lu1).
- *Risks:* JSON contract `{message,status}` and 200 codes pinned by `FavoriteTest`.
- *Required tests:* full suite; focus `FavoriteTest` (6).

**Step 9 — Feedback**
- *Files:* `FeedbackController.php`; new `FeedbackRepository.php`, `FeedbackService.php`.
- *Goal:* Move create/update/delete workflow (L-DFD P4 -> D3; UC-lu2/lu3). Mass-assignment shape (`$request->all()` -> explicit field pass-through identical to today's effective columns) and missing ownership checks (G6) preserved.
- *Risks:* redirect targets differ between store (`singlePage`) and update/destroy (`feedback.index`) — keep exact.
- *Required tests:* full suite; focus `FeedbackTest` (7) incl. cascade + hard-delete pins.

**Step 10 — Public catalog reads (SEQ-2 core)**
- *Files:* `UserSideController.php` (methods `landing`, `category`, `singlePage`, `about`, `contact`, `faqs`); new `ProductRepository.php` (reads + cheapest-price subquery + brand-count aggregation), `PriceHistoryRepository.php` (the 3-table join, ok-only, ordered asc), `CatalogService.php`; reuse `CategoryRepository`, `FaqRepository`.
- *Goal:* The L-DFD P2 / P-DFD p1 / SEQ-2 flow becomes PL->BLL->DAL. Repository owns SQL verbatim (MIN subquery, `whereHas` category filter, Scout search branch, paginate 7/15); service owns assembly (latest-per-store map, chart series, decoded description, related-products selection). G4 (500 on unknown id) preserved.
- *Risks:* **highest test sensitivity** — `LandingTest` asserts `cheapest_price` is the MIN; `SinglePageTest` asserts grouping/chart/`failed`-exclusion. Move queries character-for-character; keep view-data keys identical.
- *Required tests:* full suite; focus `LandingTest`, `CategoryPageTest`, `SinglePageTest`, `StaticPagesTest`, AccessMatrix SEC-F-04.

**Step 11 — Product admin CRUD + pivot sync**
- *Files:* `ProductController.php` (methods `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`); extend `ProductRepository` (writes), new `StoreProductRepository.php`, `ProductService.php`.
- *Goal:* Move admin product workflow (L-DFD P5; P-DFD p8 -> D1 products): spec key=>value `array_combine`->JSON encoding, parallel-array pivot attach, `updateExistingPivot` loop, attach-new-only rule, and *invoke* config sync (still controller-local until Step 13 — call stays as-is this step). `show` reuses `PriceHistoryRepository` from Step 10.
- *Risks:* parallel-array index alignment is fragile — copy loops verbatim; no behavior "improvements". Soft-delete + config-sync call sites preserved.
- *Required tests:* full suite; **add product store/update/destroy characterization tests first** (largest coverage gap, ADM-PROD specs in testing-strategy section M8); `AccessMatrixTest` product routes; `SinglePageTest` regression.

**Step 12 — fetchSpecs & spec mapping (reflection-test constraint)**
- *Files:* `ProductController.php` (`fetchSpecs`, `getBestMatch`, `detectComponentType`, `mapSpecs`); new `ComponentSpecReader.php` (DAL: SQLite PDO exact -> LIKE -> word-fallback), new `SpecMappingService.php` (BLL: fuzzy `similar_text` >=30 selection, type detection, per-type spec shaping).
- *Goal:* Extract the SQLite integration (P-DFD p8 `fetchSpecs`; scraper-adjacent store) into DAL+BLL. **`ProductController` keeps thin private delegates named `detectComponentType` and `mapSpecs`** so `ProductSpecMappingTest`'s reflection keeps passing unmodified.
- *Risks:* reflection constraint (section 0.1); fuzzy-match threshold semantics must be identical.
- *Required tests:* full suite; focus `ProductSpecMappingTest` (19, unedited).

**Step 13 — Scraper config contract (PHP<->Python)**
- *Files:* `ProductController.php` (`syncScraperConfig` removed), `ProductService.php` (calls the new service at the same 3 call sites); new `ScraperConfigStore.php` (DAL: file read/write, pretty-print format), new `ScraperConfigService.php` (BLL: which store_products enter the file; excludes soft-deleted products, keyed by store name).
- *Goal:* Give the P6 input contract a proper owner (fixes the worst Physical-DFD violation from section 2.3). Output bytes identical so the Python side (SEQ-1) is untouched.
- *Risks:* contract drift would silently break scraping (R3 Critical) — pin with a new characterization test asserting regenerated `config.json` content shape (test-only addition, temp-path file isolation per testing-strategy P-03).
- *Required tests:* full suite + new config-contract test; product characterization tests from Step 11.

**Step 14 — Product images admin**
- *Files:* `ProductImageController.php`; new `ProductImageRepository.php`, `ProductImageService.php`; reuse `ImageUploadService`.
- *Goal:* Move multi-upload workflow (P-DFD p9_img -> D2 product_images). **The broken `destroy` (G9) is replicated exactly** — same parameter resolution failure mode, no silent fix.
- *Risks:* `$fillable=[]` means insert-only path must remain `insert()`; G9 replication must be deliberate, not "accidentally repaired".
- *Required tests:* full suite; add characterization test documenting current destroy behavior (per testing-strategy ADM-IMG-F-05).

**Step 15 — Dashboard**
- *Files:* `DashboardController.php`; new `DashboardService.php`; extend `UserRepository`, `CategoryRepository`, `ProductRepository`, `StoreRepository`, `FeedbackRepository` with the aggregate queries (counts, per-category counts, 6-month registration buckets, ratings distribution, top-6 by avg rating).
- *Goal:* P-DFD p7_dash -> D1–D7 aggregate reads become DAL queries + BLL shaping.
- *Risks:* low-medium; chart ordering/labels (month buckets, rating index 1–5) must match.
- *Required tests:* full suite; `AccessMatrixTest` dashboard entry.

**Step 16 — Scraper trigger**
- *Files:* `ScraperController.php`; new `ScraperRunnerService.php` (owns `Artisan::call('scraper:run')`, exit-code->flash mapping), extend `PriceHistoryRepository` (lastRun, recentCount). `RunScraperCommand` unchanged.
- *Goal:* P-DFD p11 -> PL delegates to BLL; python invocation path byte-identical (SEQ-1 preserved).
- *Risks:* low; flash message composition (`' Output: '...`) pinned.
- *Required tests:* full suite; `AccessMatrixTest` scraper routes.

**Step 17 — PC Builder**
- *Files:* `BuildController.php`; new `BuildRepository.php` (builds + build_parts + total-price aggregate), `BuilderService.php` (save transaction, `category_name` snapshot, list-by-owner, delete); adjust `BuildCompatibilityService` to receive products from `ProductRepository` (same rule code).
- *Goal:* Migrate the undocumented module (gap G2) fully: builder pages/JSON (UC extension of g1/lu), save transaction, owner-scoped list/delete (`BuildPolicy` stays in PL).
- *Risks:* transaction boundary must wrap exactly the same two inserts; JSON shapes pinned by `BuilderTest`.
- *Required tests:* full suite; focus `BuilderTest` (18), `BuildCompatibilityServiceTest` (16, unedited assertions), `BuildPolicyTest` (2).

**Step 18 — Restore consolidation**
- *Files:* `showRestore`/`restore` methods in `UserController`, `CategoryController`, `StoreController`, `ProductController`, `ContactController`, `FaqsController`; new `RestoreService.php`; restore queries already in repositories.
- *Goal:* Collapse 6 duplicated flows (P-DFD p13; UC-sa2) into one service; per-entity flash text and redirect routes stay entity-specific and identical.
- *Risks:* flash-message drift (each entity has different text) — keep verbatim; null-`find()` fatal behavior (REST-F-07 defect) preserved.
- *Required tests:* full suite; focus `AccessMatrixTest` restore matrix (SEC-F-02).

**Step 19 — Guardrails & close-out**
- *Files:* docs + optional new architecture test (test-only).
- *Goal:* (a) Add an automated layer-dependency test asserting controllers never reference `DB`/`PDO`/model query builders (new test file only — strengthens the suite without touching production code). (b) Update `docs/diagrams.md` Physical DFD annotations to name the services/repositories behind each process (documentation only). (c) Record that gaps G1–G15 remain open defects, owned by a separate bug-fix backlog — explicitly **not** part of this migration.
- *Risks:* none.
- *Required tests:* full suite green, now including the layer guard.

---

## 7. What This Plan Deliberately Avoids

- **No interfaces per repository, no DTOs, no FormRequests, no events/CQRS/mediator** — three layers only, per the mandate; Laravel conventions (validation in controllers, Eloquent as the DAL's ORM, policies/middleware in PL) are respected as framework constraints.
- **No bug fixes.** G1–G15 are preserved behaviors; the migration changes *where* code lives, never *what* it does.
- **No changes to** routes, views, middleware, models, the Python scraper, the database, or the test suite's existing assertions — the only test changes allowed are *added* characterization tests (Steps 2–5, 11, 13, 14) and the optional layer guard (Step 19).
- **No skipped steps:** each step is mergeable on its own, in the written order, with the full suite green at every boundary.
