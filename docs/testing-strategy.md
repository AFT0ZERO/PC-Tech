# PC-Tech — Comprehensive Testing Strategy (Pre-Refactoring Baseline)

> Status: ANALYSIS & PLAN ONLY. No test code, no implementation code, no refactoring.
> Primary reference: `/docs/diagrams.md` (DB Schema, Logical DFD, Physical DFD, Sequence Diagram, Use Case Diagram), verified line-by-line against the actual codebase.
> Every test specification below carries a **trace tag** back to the diagram element it derives from:
> `[DB]` schema table/ref · `[L-DFD-Pn]` logical process n · `[L-DFD-Dn]` logical data store n · `[P-DFD-*]` physical flow · `[SEQ-1]` scraper sequence · `[SEQ-2]` product-page sequence · `[UC-*]` use case.

---

## 1. System Inventory (As-Built, Verified Against Code)

### 1.1 Technology Stack
| Layer | Technology |
|---|---|
| Backend | Laravel 10 / PHP 8.1, Blade, Laravel UI auth, Sanctum, Scout (search) |
| DB | MySQL (`pc_tech`), Eloquent, SoftDeletes on most entities |
| Price scraper | Python 3 microservice: `requests`+BS4 (static), Playwright (dynamic), `mysql-connector-python` (direct DB writes) |
| Specs DB | `scraper/components.sqlite` read directly via PDO from `ProductController::fetchSpecs()` |
| Test tooling present | PHPUnit 10, Mockery, Faker (Pest plugin allowed but not installed); **no Python test framework installed** |

### 1.2 Module Catalog
| # | Module | Key Classes | Diagram Coverage |
|---|---|---|---|
| M1 | Authentication & Registration | `Auth\*Controller` (Laravel UI), `HomeController` | L-DFD P1, UC-g3 |
| M2 | Public Browsing & Price Comparison | `UserSideController` (landing, category, singlePage, about, contact, faqs) | L-DFD P2, P-DFD public, SEQ-2, UC-g1/g2 |
| M3 | Favorites | `FavoriteController` | L-DFD P3/D3, UC-lu1 |
| M4 | Feedback & Ratings | `FeedbackController` | L-DFD P4/D3, UC-lu2/lu3, UC-a4 |
| M5 | User Account | `UserSideController::account/updateAccount/updatePassword` | P-DFD auth-user p6, UC-lu4 |
| M6 | PC Builder (**UNDOCUMENTED in diagrams**) | `BuildController`, `BuildCompatibilityService`, `BuildPolicy`, `Build`, `BuildPart` | — (gap, see §4) |
| M7 | Admin Dashboard | `DashboardController` | P-DFD admin p7, UC-a1..a5 |
| M8 | Admin Products + Specs + Scraper-config sync | `ProductController` | P-DFD admin p8, L-DFD P5, UC-a1 |
| M9 | Admin Product Images | `ProductImageController` | P-DFD admin p9_img |
| M10 | Admin Categories | `CategoryController` | P-DFD admin p9_cat, L-DFD P5 |
| M11 | Admin Stores | `StoreController` | P-DFD admin p7_store |
| M12 | Admin Users & Admin Profile | `UserController` | P-DFD admin p8_user, UC-sa1 |
| M13 | Admin FAQs | `FaqsController` | P-DFD admin p9_faq |
| M14 | Contacts (public form + admin inbox) | `ContactController` | P-DFD public p2, admin p10, UC-g4/a3 |
| M15 | Scraper Trigger (web + console) | `ScraperController`, `RunScraperCommand` | P-DFD admin p11, UC-a2 |
| M16 | Python Scraper Microservice | `scraper.py`, `StaticScraper`, `DynamicScraper`, `BaseScraper`, `db/connection.py`, `db/writer.py` | L-DFD P6/D4, P-DFD python, SEQ-1 |
| M17 | Authorization (middleware + policy) | `AdminOrSuperAdmin`, `Superadmin`, `Authenticate`, `BuildPolicy` | Use case actor hierarchy |
| M18 | Soft-Delete Restore (super-admin) | `showRestore()/restore()` in 6 controllers | P-DFD super-admin p13, UC-sa2 |

### 1.3 Actors (Use Case Diagram)
Guest → Login User → Admin → Sup Admin (strict capability inheritance). Role values in DB: `user`, `admin`, `super-admin` (`users.role` varchar).

### 1.4 Background Jobs / Scheduled Tasks / Events / External Integrations
| Type | Item | Notes |
|---|---|---|
| Scheduled task | System **cron `0 */6 * * *`** runs `python scraper.py` (OS-level, NOT Laravel scheduler — `app/Console/Kernel.php` schedule is empty) | SEQ-1 trigger |
| Console command | `scraper:run {--store=}` → `exec()` of python | invoked by cron-equivalent & admin UI |
| Events/Listeners | **None** (EventServiceProvider stock) | — |
| Queues/Jobs | **None** (`QUEUE_CONNECTION=sync`, no Job classes) | — |
| External integrations | Store websites (mcc-jo, etc.) over HTTP; MySQL shared between PHP & Python; SQLite components DB; Laravel Scout driver (default Algolia per `config/scout.php`) | P6, fetchSpecs |
| File system | Image uploads to `public/uploads/*`; `scraper/config.json` rewritten by PHP (`syncScraperConfig`); `scraper/logs/scraper.log` | cross-process coupling |

### 1.5 Complete Route/Endpoint Map (from `routes/web.php`; `api.php` has only stock `/user`)
| Method & Path | Handler | Middleware | Trace |
|---|---|---|---|
| GET `/` | UserSideController@landing | — | P2, SEQ-2 |
| GET `/builder` | BuildController@index | — | undocumented |
| GET `/builder/parts/{category}` | BuildController@getParts | — | undocumented |
| POST `/builder/check-compatibility` | BuildController@checkCompatibility | — (CSRF) | undocumented |
| GET `/category/{id?}`, `/category` | UserSideController@category | — | P2 |
| GET `/single-page/{id}` | UserSideController@singlePage | — | P2, SEQ-2 |
| GET `/About`, `/Contact Us`, `/FAQs` | UserSideController@about/contact/faqs | — | P-DFD public p1 |
| GET `/hi` (name=`login`!) | closure `dd('here')` | — | debug leftover, name collision risk |
| Auth::routes() | login/register/logout/password reset | guest/auth | P1, UC-g3 |
| GET `/home` | HomeController@index | auth | stock |
| POST `/builder/save` · GET `/builder/my-builds` · DELETE `/builder/{build}` | BuildController | auth | undocumented |
| POST `/favorite/{productId}` · DELETE `/favorites/remove/{product}` · GET `/favorites/list` | FavoriteController | auth | P3, UC-lu1 |
| GET `/User-Account` · PUT `/User-Account/password` · PUT `/User-Account/{user}` | UserSideController | auth | UC-lu4 |
| resource `feedback` (all 7) | FeedbackController | auth | P4 |
| GET `/dashboard` | DashboardController@index | auth + admin-or-super-admin | UC-a* |
| `dashboard/admin*` profile & password | UserController | auth + admin-or-super-admin | UC-a5 |
| resource `dashboard/users` + restore-u | UserController | admin-or-super-admin (+ super-admin on restore) | UC-a1, sa1, sa2 |
| `dashboard/categories` CRUD + restore-c | CategoryController | same pattern | P5 |
| `dashboard/stores` CRUD + restore-s | StoreController | same pattern | P5 |
| `dashboard/products` CRUD + `fetch-specs` + restore-p | ProductController | same pattern | P5 |
| `dashboard/scraper` GET, `/scraper/run` POST | ScraperController | admin-or-super-admin | UC-a2 |
| `dashboard/product/{product}/upload` GET/POST/DELETE | ProductImageController | admin-or-super-admin | P-DFD p9_img |
| `dashboard/contacts` GET/POST/GET{id}/DELETE + restore-co | ContactController | admin-or-super-admin | UC-a3 |
| `dashboard/faqs` full resource + restore-f | FaqsController | admin-or-super-admin | P5 |

---

## 2. Diagram Traceability Analysis

### 2.1 From DB Schema `[DB]` — entities & relationships requiring tests
| Entity | Must-test aspects |
|---|---|
| `users` | soft delete; role enum-like values (`user`/`admin`/`super-admin`); **deleting a user cascades `contacts` (model boot)**; password hashing; Scout `toSearchableArray` |
| `categories` | soft delete; **deleting cascades `products` (model boot)**; hasMany products |
| `products` | soft delete; belongsTo category; hasMany images; belongsToMany stores (pivot `store_product` with price/url/status); **`socket`, `form_factor`, `tdp` columns exist in code but NOT in schema diagram** |
| `product_images` | soft delete; belongsTo product; `$fillable = []` (mass-assignment blocked — only `insert()` works) |
| `stores` | soft delete; belongsToMany products |
| `store_product` | pivot w/ `product_price` decimal(8,2), `product_url`, `product_status`; **NO unique(store_id,product_id)** — duplicate attach possible |
| `price_history` | `sp_id → store_product.id` FK cascade; `status` enum ok/failed; `scraped_at` default; **PriceHistory model references `App\Models\StoreProduct` which does NOT exist** (broken relation) |
| `feedback` | `rate` tinyint 1–5; product & user FKs cascade; **no soft deletes (hard delete)** |
| `favorite` | pivot with its own id + timestamps; **NO unique(user_id,product_id)** |
| `contacts` | soft delete; nullable-looking `user_id` FK |
| `faqs` | soft delete |
| *(undocumented)* `builds`, `build_parts` | FK cascades, soft delete on builds; `build_parts.category_name` snapshot |

DB-level integration tests required for: every FK cascade path, every soft-delete scope, every model-boot cascade (User→contacts, Category→products), pivot attach/detach/updateExistingPivot, and the `price_history.sp_id` join chain used by SEQ-2 queries.

### 2.2 From Logical DFD — business processes → test scenarios
| Process | Inputs → Outputs (data changes) | Required scenario coverage |
|---|---|---|
| **P1 Manage authentication** | credentials → session; new user → D1 insert (role forced `user`) | register, login, logout, wrong password, reset password, role assignment cannot be hijacked |
| **P2 Browse & compare** | browse request → read D2 + D4 → product list with **MIN(product_price) per product** and **per-store latest price + full history** | landing pagination, category filter/brand counts, search, single page price table & chart data grouping |
| **P3 Manage favourites** | add/remove → D3 insert/delete row | toggle add, toggle remove, idempotency, list, unauthorized access |
| **P4 Submit feedback** | review → D3 insert/update/delete | store validation, rate bounds 1–5, ownership concerns (see risks) |
| **P5 Manage catalogue** | admin CRUD → D2 changes | full CRUD + validation + image upload + soft delete for products/categories/stores/images/faqs/users |
| **P6 Collect prices** | store HTML → D4 insert; **also UPDATEs store_product.product_price & product_url** (side effect in `writer.py`) | static parse, dynamic parse, retry, failed-status row, price cleaning, sp_id resolution |

### 2.3 From Physical DFD — HTTP workflows → integration (feature) tests
- Public flows p1/p2: GET pages render Blade with data from products/categories/faqs/price_history; **POST contact insert into contacts** — ⚠ the physical DFD shows this as a **public** route, but in code it exists ONLY behind `admin-or-super-admin` (see §4 gap G1).
- Auth flows p3–p6: session issuance, favorite INSERT/DELETE with JSON response, feedback INSERT/UPDATE, users UPDATE.
- Admin flows p7–p11: aggregate stats; products/images/categories/stores/users/faqs CRUD; contacts SELECT/DELETE; scraper `INSERT price_history via shell_exec` (actual implementation: `Artisan::call('scraper:run')` → `exec`, not direct `shell_exec` in controller — behavior equivalent).
- Super-admin flow p13: `deleted_at = NULL` on 6 entities.
- Python flows p14–p17: config load → static/dynamic fetch → parse → `INSERT price_history` + log file.

### 2.4 From Sequence Diagram — end-to-end order verification
- **SEQ-1 (scraper run)**: cron → scraper.py → load config.json → GET product URL → parse selector → **retry on failure (×3, exponential backoff)** → INSERT price_history → DB returns ok/failed → write scraper.log. Tests must assert: order of operations, retry count/backoff, a `failed` row written when parsing fails, log line emitted, one bad store does not abort remaining stores.
- **SEQ-2 (product page)**: GET /single-page/{id} → SELECT product (with stores, images, category) → SELECT price_history (join store_product, join stores, `status='ok'`, order by scraped_at asc) → render Blade. Tests must assert: both queries' data present in view, **latest-per-store grouping**, chart series grouping by store, `failed` rows excluded.

### 2.5 From Use Case Diagram — every capability → ≥1 scenario
| Use case | Scenario IDs (defined in §8) |
|---|---|
| g1 Browse website/products | PUB-F-01..08 |
| g2 Compare store prices | PUB-F-05..08, SEQ2-F-01..03 |
| g3 Login/Register | AUTH-F-01..12 |
| g4 Submit contact form | CON-F-01..06 (**currently impossible for guests — G1**) |
| lu1 Manage favourites | FAV-F-01..07 |
| lu2 Rate products | FB-F-01..05 |
| lu3 Submit feedback | FB-F-01..08 |
| lu4 Manage account | ACC-F-01..08 |
| lu5 All guest capabilities | covered by PUB-* executed as logged-in user |
| a1 Manage products/categories/stores/users | ADM-PROD-*, ADM-CAT-*, ADM-STORE-*, ADM-USER-* |
| a2 Trigger scraper | SCRAP-F-01..05 |
| a3 View contact msgs | CON-F-07..10 |
| a4 View feedback | DASH-F-03, FB-F-09 |
| a5 All user capabilities | re-run user suites as admin actor |
| sa1 Manage admin | ADM-USER-01..10 |
| sa2 Restore deleted records | REST-F-01..12 |
| sa3 All admin capabilities | middleware matrix test SEC-F-01..04 |

---

## 3. Critical Business Logic (highest test value)

1. **Cheapest-price computation** — correlated subquery `MIN(product_price)` per product used on landing, category, builder parts, and build total. Wrong results = wrong prices shown sitewide. `[L-DFD-P2]`
2. **Price-history grouping** — latest-per-store map + full chart series, `status='ok'` filter, join across `price_history → store_product → stores`. `[SEQ-2]`
3. **BuildCompatibilityService rules** — 4 rules: CPU↔MB socket (with regex fallback `extractSocket`), MB↔Case form factor, Cooler TDP ≥ CPU TDP, PSU wattage ≥ Σ TDP of non-PSU parts. Pure logic → ideal unit-test target.
4. **Build save transaction** — total price = Σ cheapest prices; `builds` + `build_parts` insert in one transaction; `category_name` snapshot.
5. **Scraper price pipeline** — `clean_price` regex parsing; retry/backoff; `failed` vs `ok` rows; `sp_id` resolution (product_id + store_name); **side-effect sync of `store_product.product_price`/URL**; PHP→`config.json` regeneration (`syncScraperConfig`) must stay consistent with what Python reads.
6. **Role-based access control** — `admin-or-super-admin`, `super-admin` middleware + `BuildPolicy` owner-only delete.
7. **Soft-delete & restore chain** — 6 restorable entities; model-boot cascades (User→contacts, Category→products).
8. **Product create/update with store pivot arrays** — index-aligned parallel arrays (`store_id[]/price[]/url[]/status[]`), `array_combine(key,value)` spec JSON, `updateExistingPivot`, attach-new-only.
9. **fetchSpecs fuzzy matching** — SQLite exact → LIKE → word-implode fallback → `similar_text` ≥30% threshold; spec mapping per component type.

---

## 4. Gaps & Discrepancies: Diagrams vs Code (must be captured by tests / fixed before refactor)

| ID | Gap | Evidence | Test implication |
|---|---|---|---|
| G1 | **Public contact form POST is missing.** Physical DFD & UC-g4 say guests submit contact; route exists only under `admin-or-super-admin` | `routes/web.php` L37 (GET only), L144 (POST inside admin group) | Write CON-F-01 as the *intended* behavior per diagram — it will FAIL today; flag as defect, then decide (fix route vs update diagram) before refactoring |
| G2 | **PC Builder module entirely absent from diagrams** (routes, `builds`/`build_parts` tables, `products.socket/form_factor/tdp` columns, `BuildCompatibilityService`, `BuildPolicy`) | routes L26–28, 56–58; migrations 2024_11_10_* | Diagrams must be updated; meanwhile treat as first-class module M6 with full coverage |
| G3 | `PriceHistory::storeProduct()` references non-existent `App\Models\StoreProduct` model | `app/Models/PriceHistory.php`; no such file in `app/Models` | Any test touching the relation fails → document as defect; tests use query-builder joins instead until fixed |
| G4 | `singlePage()` uses `find($id)` then dereferences → **500 instead of 404** for unknown/missing product; `json_decode` on non-JSON description may return null | `UserSideController.php` L69–77 | Edge-case tests specify current vs expected behavior |
| G5 | **IDOR**: `PUT /User-Account/{user}` has no ownership check — any authenticated user can edit ANY user | `UserSideController::updateAccount` | Security test ACC-F-07 specifies expected 403 — fails today; flag |
| G6 | **IDOR/mass-assignment**: feedback `store`/`update` accept `user_id` from request (`$request->all()`); `update`/`destroy` have no ownership check | `FeedbackController.php` | Security tests FB-F-06/07 specify expected behavior — fail today; flag |
| G7 | Registration: **email NOT unique-validated** (mobile is); password `min:5` weak | `RegisterController.php` validator | Validation tests expose; flag as requirement gap |
| G8 | Admin can create/promote users incl. `role` changes — "Manage Admin" is documented as **Sup Admin** capability (UC-sa1) but routes sit under `admin-or-super-admin`; `update()` accepts arbitrary `role` incl. `super-admin` | `UserController::update` L111 | Privilege-escalation test ADM-USER-09 specifies expectation; flag |
| G9 | `ProductImageController::destroy(ProductImage $productImage, $id)` — route supplies `{product}` only; `$productImage` unresolvable, `$id` null; also deletes `$ProductImage->path` — **property doesn't exist** (column is `image`) so files leak | controller L54–63 | Feature test documents broken behavior; flag before refactor |
| G10 | Debug route `/hi` named `login` collides with auth `login` route name | `web.php` L41–43 | Route smoke test asserts `route('login')` → `/login` |
| G11 | `phpunit.xml` has sqlite memory DB **commented out** → tests would run against real MySQL; Scout default driver `algolia` would make network calls | phpunit.xml, config/scout.php | Infra prerequisites P-01/P-02 mandatory before any test run |
| G12 | No unique constraints on `favorite(user_id,product_id)` / `store_product(store_id,product_id)` | migrations | Race/duplicate edge tests document current behavior |
| G13 | `Store` model uses `softDeletes, SoftDeletes` twice (case-variant duplicate trait) — harmless but smells | `app/Models/Store.php` | — |
| G14 | Scraper cron is OS-level (`0 */6 * * *`), not in Laravel scheduler — docs imply cron; nothing to test in Laravel, but sequence must be tested at Python level | `Console/Kernel.php` empty | — |
| G15 | `BuildController@getParts` ignores `product_status` (out-of-stock items listed, price 0 when no store row) | controller L36–55 | Edge tests document |

---

## 5. Risk Register (drives priorities)

| Risk | Area | Why risky | Priority |
|---|---|---|---|
| R1 Security (IDOR, privilege escalation, mass assignment) | M4, M5, M12 | G5, G6, G8 — data tampering across users | **Critical** |
| R2 Wrong prices displayed / stored | M2, M6, M16 | MIN-price subquery, scraper sync side-effect | **Critical** |
| R3 Scraper pipeline silently failing | M15/M16 | external HTTP, regex parsing, direct DB writes, config rewritten by PHP | **Critical** |
| R4 Compatibility rules wrong | M6 | users misled about hardware compatibility | **High** |
| R5 Data loss via cascades | M10, M12 | Category delete → products; User delete → contacts | **High** |
| R6 Broken restore flows | M18 | 6 near-identical restore implementations, `find()` may return null | **High** |
| R7 File upload handling | M5, M8–M12 | client-supplied extension, `time()` collisions, public path writes | **Medium** |
| R8 Scout search unavailable in tests/prod | M2, M13, M14 | external driver | **Medium** |
| R9 Broken image-delete code path | M9 | G9 | **Medium** |
| R10 Session/flash regressions in admin CRUD | M8–M14 | UX-only, cheap to cover | **Low** |

---

## 6. Test Environment & Infrastructure Prerequisites (Phase 0 — blocking)

| ID | Prerequisite |
|---|---|
| P-01 | Configure PHPUnit test DB: uncomment/set `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:` (or dedicated `pc_tech_test` MySQL). **Never run against dev DB.** |
| P-02 | Set `SCOUT_DRIVER=collection` (Scout 10 built-in) for searchable models in tests — avoids Algolia network calls while keeping `Model::search()` functional enough for feature tests; document that relevance/ranking is NOT tested. |
| P-03 | Disable/scrub external side effects in tests: prevent `syncScraperConfig()` from writing real `scraper/config.json` (run tests with a copied scaffold or assert via a temp path); never let `exec()` fire real python from feature tests — cover the command separately with a stubbed script. |
| P-04 | Create missing factories: `FeedbackFactory`, `BuildFactory`, `BuildPartFactory`, `PriceHistoryFactory`, and a `store_product` pivot helper (attach-with-pivot state). Existing factories: User, Category, Product, ProductImage, Store, Contact, Faqs. |
| P-05 | Add per-role user states to `UserFactory` (`user()`, `admin()`, `superAdmin()`). |
| P-06 | Python: create venv under `scraper/`, add dev deps `pytest`, `pytest-asyncio`, `responses` (or `requests-mock`), `pytest-mock`. No project code changes. |
| P-07 | Seed-free tests: every test builds its own data via factories (`RefreshDatabase`). |
| P-08 | Filesystem safety: image-upload tests write to a temp dir or assert via `Storage`/file mocks; clean up `public/uploads` artifacts after suite. |

---

## 7. Global Testing Conventions
- **Unit** = no DB, no HTTP (pure logic: service, middleware decision, policy, price parser). **Feature/Integration** = full HTTP cycle + DB assertions (Laravel), or module + real MySQL test DB (Python writer, marked `@pytest.mark.integration`, optional in CI).
- Every admin endpoint is tested through a **4-actor matrix**: guest, user, admin, super-admin.
- Assertions always include: HTTP status, DB state, and (for redirects) session flash — matching the controllers' actual behavior.
- Diagram-derived invariants (e.g., "failed rows excluded from price display" per SEQ-2) are asserted explicitly.

---

## 8. Per-Module Test Specifications

### M1 — Authentication & Registration `[L-DFD-P1, D1; P-DFD p3; UC-g3]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| AUTH-F-01 | Feature | Register with valid data → user row created with **role=`user`**, password hashed (not plaintext), redirected per app flow | P1 must persist user to D1 safely; role injection via request must not work | Critical |
| AUTH-F-02 | Feature | Register validation failures: missing fname/lname, fname <3 chars, invalid email, mobile <9/non-numeric/**duplicate mobile**, gender missing, password <5, password confirmation mismatch | Exact validator rules in RegisterController | High |
| AUTH-F-03 | Feature | Duplicate **email** registration — documents G7 (currently allowed). Assert current behavior + mark expected-unique as `testdox` TODO | Requirement gap | High |
| AUTH-F-04 | Feature | Login with valid credentials → authenticated session, redirect to `landing` (both branches of `authenticated()` go to landing for all roles) | P1 session token flow in P-DFD p3 | Critical |
| AUTH-F-05 | Feature | Login wrong password / unknown email → error, guest remains | error case | High |
| AUTH-F-06 | Feature | Logout → session invalidated, protected route redirects to login | P1 logout | High |
| AUTH-F-07 | Feature | Guest hitting `auth` routes (favorites, account, builder/save, feedback) → redirect to login | Authenticate middleware contract | Critical |
| AUTH-F-08 | Feature | `route('login')` resolves to `/login`, not `/hi` (G10) | debug route collision | Medium |
| AUTH-F-09 | Feature | Password reset request → notification/mail faked & queued; reset with valid token changes password; invalid token rejected | P1 resetPassword branch (Mail fake) | Medium |
| AUTH-F-10 | Feature | Authenticated user visiting login/register → redirected (RedirectIfAuthenticated) | guest middleware | Low |
| AUTH-U-01 | Unit | `AdminOrSuperAdmin` middleware: pass for role admin; pass for super-admin; 403 for role user; 403 for guest (null user) | gate for ALL admin routes; cheap pure-logic test | **Critical** |
| AUTH-U-02 | Unit | `Superadmin` middleware: pass only super-admin; 403 for admin and user | gate for restore routes (UC-sa2) | **Critical** |

### M2 — Public Browsing & Price Comparison `[L-DFD-P2, D2, D4; P-DFD p1; SEQ-2; UC-g1/g2]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| PUB-F-01 | Feature | GET `/` 200; view has categories, latest products paginated(7), category-1 products; each product exposes `cheapest_price` = MIN across its store rows | core landing per P2 | Critical |
| PUB-F-02 | Feature | `cheapest_price` correctness: product with 3 store prices shows the MIN; product with NO store rows → cheapest_price null/0 handled without error | R2 price correctness | Critical |
| PUB-F-03 | Feature | GET `/category` (no id) → all products paginate(15) + brand counts grouped over ALL products | P2 browse | High |
| PUB-F-04 | Feature | GET `/category/{id}` → only that category's products; brand counts filtered to that category; invalid id (e.g., 99999) → empty list, 200 | filter correctness + edge | High |
| PUB-F-05 | Feature | GET `/single-page/{id}` → 200; view receives product (with stores/images/category), decoded description array, feedbacks, `priceHistory` = **latest row per store**, `priceHistoryChart` = all points per store ordered asc | SEQ-2 exact interaction result | Critical |
| PUB-F-06 | Feature | price_history with `status='failed'` rows → excluded from table AND chart | SEQ-2 `where status ok` invariant | Critical |
| PUB-F-07 | Feature | `/single-page/{unknown-id}` → documents G4 (currently 500). Expected: 404 — write as failing-spec, tag defect | error case | High |
| PUB-F-08 | Feature | Product with no price history → page still renders; empty chart series | edge | Medium |
| PUB-F-09 | Feature | Search `?search=` on `/category` uses Scout (driver=collection per P-02): matching name/brand returned, non-matching excluded | P2 search branch | Medium |
| PUB-F-10 | Feature | GET `/About`, `/Contact Us`, `/FAQs` → 200; FAQs page lists all FAQ rows | static + D3 read | Medium |
| PUB-F-11 | Feature | Pagination: seed 20 products → page 2 reachable, 5 items | boundary | Low |
| PUB-F-12 | Feature | All public pages accessible as Guest AND as logged-in user (UC-lu5) | actor inheritance | Medium |

### M3 — Favorites `[L-DFD-P3, D3; P-DFD p4; UC-lu1]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| FAV-F-01 | Feature | POST `/favorite/{productId}` when absent → JSON `status=added`; row in `favorite` for user+product | P3 insert into D3 | Critical |
| FAV-F-02 | Feature | POST again → JSON `status=removed`; row deleted | toggle contract | Critical |
| FAV-F-03 | Feature | Toggle with non-existent productId → documents current FK-exception behavior (expected: 404/422 — tag defect) | error case, G12-adjacent | High |
| FAV-F-04 | Feature | Guest POST → redirect to login (no JSON) | auth boundary | Critical |
| FAV-F-05 | Feature | DELETE `/favorites/remove/{product}` → detached, JSON success | list-page removal path | High |
| FAV-F-06 | Feature | GET `/favorites/list` → partial view contains only THIS user's favorites with images loaded | D3 read per user | High |
| FAV-F-07 | Feature | Double-toggle rapid sequence → exactly 0 rows remain; note missing unique constraint (G12) — no duplicate rows created by toggle logic itself | idempotency | Medium |
| FAV-U-01 | Unit | `User::favorites()` relation is belongsToMany via `favorite` table with correct keys | relation wiring | Medium |

### M4 — Feedback & Ratings `[L-DFD-P4, D3; P-DFD p5; UC-lu2/lu3/a4]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| FB-F-01 | Feature | POST `/feedback` valid (message, rate 1–5, product_id, user_id) → row created; redirect to `singlePage` with success flash | P4 insert into D3 | Critical |
| FB-F-02 | Feature | Validation matrix: rate missing/0/6/non-integer; message missing; product_id not exists; user_id not exists → session errors, no row | exact validator bounds | High |
| FB-F-03 | Feature | Boundary: rate=1 and rate=5 accepted | boundary | High |
| FB-F-04 | Feature | Guest POST → login redirect | auth boundary | Critical |
| FB-F-05 | Feature | PUT `/feedback/{id}` valid update → row changed, redirect to index | P4 update | High |
| FB-F-06 | Feature | **Security spec (fails today, G6):** user A cannot update/delete user B's feedback — expected 403; and `store` must ignore/substitute client-sent `user_id` with `Auth::id()` | R1 IDOR | Critical |
| FB-F-07 | Feature | DELETE `/feedback/{id}` → hard-deleted (assert `assertDatabaseMissing`, and `withTrashed` N/A — no SoftDeletes on model) | schema says no deleted_at on feedback | High |
| FB-F-08 | Feature | Deleting product/user cascades feedback removal (FK cascade) | DB ref validation | Medium |
| FB-F-09 | Feature | Admin "view feedback" (UC-a4): feedback visible via dashboard stats (avg ratings) — covered via DASH-F-03 | UC-a4 trace | Low |

### M5 — User Account `[P-DFD p6; UC-lu4]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ACC-F-01 | Feature | GET `/User-Account` as user → 200 own data | lu4 | High |
| ACC-F-02 | Feature | PUT `/User-Account/{user}` valid (no image) → fields updated, redirect account, success flash | P-DFD p6 UPDATE users | Critical |
| ACC-F-03 | Feature | With valid jpeg/png/jpg image → file stored under `uploads/user/`, user.image path updated; invalid mime (gif/svg/exe) → validation error | upload validation R7 | High |
| ACC-F-04 | Feature | Validation: fname<3, bad email, mobile<9 or non-numeric, gender missing → errors | validator parity | High |
| ACC-F-05 | Feature | PUT `/User-Account/password`: wrong current_password → `current_password` error; mismatch confirmation → error; valid → password changed & old password no longer authenticates, new one does | password flow | Critical |
| ACC-F-06 | Feature | Password <8 rejected | boundary | Medium |
| ACC-F-07 | Feature | **Security spec (fails today, G5):** user A PUT `/User-Account/{userB}` → expected 403 | R1 IDOR | Critical |
| ACC-F-08 | Feature | Guest access → login redirect | auth boundary | High |

### M6 — PC Builder `[diagram GAP G2 — treat as required module]`
**Unit — `BuildCompatibilityService` (pure, highest-value unit suite in the project):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| BLD-U-01 | Empty array → `[]` warnings | guard clause | High |
| BLD-U-02 | CPU+MB same socket (explicit `socket` attr) → no warning; different sockets → socket-mismatch warning string contains both values | Rule 1 core | **Critical** |
| BLD-U-03 | Socket fallback: `socket` null → regex extracts from brand string (`AM4`, `AM5+`, `LGA1700`, `LGA 1700`, `TR4`, `FM2+` variants; non-matching brand → null → no warning) | `extractSocket` regex branches | High |
| BLD-U-04 | Case-insensitive socket comparison (`am4` vs `AM4`) | strtolower branch | Medium |
| BLD-U-05 | MB+Case same form factor → no warning; different → form-factor warning | Rule 2 | **Critical** |
| BLD-U-06 | Cooler TDP < CPU TDP → cooling warning with watt numbers; equal → none; greater → none; either TDP null → none | Rule 3 + null guards | **Critical** |
| BLD-U-07 | PSU wattage < Σ TDP of non-PSU parts → power warning; PSU excluded from its own sum; total 0 → no warning; wattage null → no warning | Rule 4 + filter logic | **Critical** |
| BLD-U-08 | Products keyed by **lowercased category name** — parts from unknown/other categories don't crash; only one part per category used | keyBy behavior | Medium |
| BLD-U-09 | Single part only (e.g., just CPU) → no warnings, no errors | partial build edge | High |
| BLD-U-10 | Multiple rules violated simultaneously → all warnings returned | aggregation | Medium |

**Unit — `BuildPolicy`:** BLD-U-11 owner can delete → true; non-owner → false. **Critical.**

**Feature — BuildController:**
| ID | Specification | Why | Priority |
|---|---|---|---|
| BLD-F-01 | GET `/builder` 200; builder categories = only the 8 slot names that exist in DB, ordered CPU→Case | slot ordering via sortBy | High |
| BLD-F-02 | GET `/builder/parts/{category}` → JSON array of that category's products with id/name/brand/cheapest_price(float)/category_name; empty category → `[]` | JSON contract for frontend | High |
| BLD-F-03 | POST `/builder/check-compatibility` with part_ids → JSON `{warnings:[...]}`; invalid product id → 422 validation; non-array → 422 | endpoint contract | High |
| BLD-F-04 | POST `/builder/save` valid → builds row (user_id=auth, name, notes, total_price=Σ MIN store prices) + one build_parts row per part with correct `category_name` snapshot; JSON success | transaction contents | **Critical** |
| BLD-F-05 | Save validation: no name, name >150, empty part_ids, part id not existing → 422; guest → login redirect | validators + boundary | High |
| BLD-F-06 | total_price with a part having NO store rows → contributes 0; build still saves | edge (R2-adjacent) | High |
| BLD-F-07 | GET `/builder/my-builds` → only own builds, newest first, products with pivot category_name eager-loaded | ownership scoping | High |
| BLD-F-08 | DELETE `/builder/{build}` owner → soft-deleted + JSON success; **other user's build → 403** (BuildPolicy enforced); guest → login | R1 + soft delete | **Critical** |
| BLD-F-09 | Deleting a product that is in a build → build_parts cascade-deleted (FK cascadeOnDelete) — documents current destructive behavior | DB ref validation | Medium |
| BLD-F-10 | Warnings are non-blocking: save succeeds even when compatibility warnings exist | documented design | Medium |

### M7 — Admin Dashboard `[P-DFD p7_dash, d_all; UC-a1..a4]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| DASH-F-01 | Feature | GET `/dashboard` as admin → 200; stat card counts equal seeded products/categories/users/stores counts | aggregate SELECT per P-DFD | High |
| DASH-F-02 | Feature | Access matrix: guest → login redirect; role user → 403; admin → 200; super-admin → 200 | middleware on dashboard | **Critical** |
| DASH-F-03 | Feature | Chart datasets: products-per-category counts; last-6-months user registration buckets (seed users in specific months); ratings distribution index 1–5; top-6 products by avg rating with correct order | each of the 4 chart queries | Medium |
| DASH-F-04 | Feature | Zero-data state (empty DB) → page renders, no division errors | edge | Low |

### M8 — Admin Products `[L-DFD-P5; P-DFD p8; UC-a1]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-PROD-F-01 | Feature | Index: search by name/brand LIKE, filter by category_id, all 4 sort options, pagination 25 with query string | admin browse P5 | High |
| ADM-PROD-F-02 | Feature | Store: valid payload (name, description, brand, category, key[]/value[], store_id[]/price[]/url[]/status[]) → product row with `description` = JSON of key⇒value pairs, `smallDescription`=description text, pivot rows per store with aligned price/url/status | P5 CRUD + spec encoding | **Critical** |
| ADM-PROD-F-03 | Feature | Store validation failures: missing each required field; non-numeric price.*; category not exists → errors, nothing persisted | validators | High |
| ADM-PROD-F-04 | Feature | Mismatched parallel arrays (2 stores, 1 price) → documents current misalignment behavior (tag defect candidate) | risky array handling | Medium |
| ADM-PROD-F-05 | Feature | `array_combine` with duplicate keys → later value wins (documents behavior) | edge | Low |
| ADM-PROD-F-06 | Feature | Update: product fields changed; spec JSON rebuilt; existing store pivot updated via updateExistingPivot (price[storeId][0] etc.); new_store_id attached only when not already attached; empty new_store_id skipped | update branches L158–188 | **Critical** |
| ADM-PROD-F-07 | Feature | Destroy → soft-deleted (deleted_at set), still in `withTrashed` | soft delete P5 | High |
| ADM-PROD-F-08 | Feature | Show: view has decoded descriptions + latest-per-store priceHistory + allHistory (status ok only) | mirrors SEQ-2 logic admin-side | Medium |
| ADM-PROD-F-09 | Feature | fetchSpecs: exact SQLite name match → mapped specs per type (cpu/gpu/ram/psu/mb/storage/unknown mapping correctness can be asserted at unit level on `mapSpecs`/`detectComponentType` via reflection or indirect feature); LIKE match; word-fallback; no results → JSON error; missing sqlite file → JSON error | G-scraper-adjacent tool used in create form | Medium |
| ADM-PROD-U-01..04 | Unit | `detectComponentType` precedence rules (motherboard before cpu; ram vs chipset guard; psu wattage) and `mapSpecs` output shaping/`array_filter` null-drop per type — pure functions | mapping bugs silently mis-fill specs | High |
| ADM-PROD-F-10 | Feature | **syncScraperConfig** (via store/update/destroy): `scraper/config.json` rewritten so each configured store's `products` = current non-deleted store_product rows with URLs; store absent from DB left untouched; soft-deleted product's URLs removed | PHP→Python contract (P6 input) | **Critical** (with P-03 file isolation) |
| ADM-PROD-F-11 | Feature | Access matrix on all product endpoints (guest/user blocked, admin ok) | middleware | **Critical** |

### M9 — Admin Product Images `[P-DFD p9_img; DB product_images]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-IMG-F-01 | Feature | GET upload page for existing product → 200 with existing images | read | Medium |
| ADM-IMG-F-02 | Feature | POST multiple valid images (png/jpg/jpeg/webp) → files stored, N rows inserted with product_id | multi-file insert path | High |
| ADM-IMG-F-03 | Feature | Invalid mime / no files → validation error, nothing inserted | validation | Medium |
| ADM-IMG-F-04 | Feature | POST to non-existent product id → 404 | findOrFail branch | Medium |
| ADM-IMG-F-05 | Feature | DELETE image → documents G9 broken behavior (unresolvable binding/`path` property); expected spec: row soft-deleted + file removed — mark defect | R9 | High |
| ADM-IMG-F-06 | Feature | Access matrix (guest/user blocked) | middleware | High |

### M10 — Admin Categories `[P-DFD p9_cat; L-DFD P5; DB cascade]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-CAT-F-01 | Feature | Index search LIKE + pagination | browse | Low |
| ADM-CAT-F-02 | Feature | Store valid (name ≥3 + required image mime) → row + file; name <3 / missing image / bad mime → errors | validation + upload | High |
| ADM-CAT-F-03 | Feature | Update with/without image → name changed; image replaced only when provided | branches | High |
| ADM-CAT-F-04 | Feature | **Destroy cascades products** (model boot): category's products become soft-deleted too | R5 data-loss path | **Critical** |
| ADM-CAT-F-05 | Feature | Restore category → `deleted_at` null; **note:** previously cascaded products stay deleted (documents current behavior — restore does NOT cascade back) | restore asymmetry edge | High |
| ADM-CAT-F-06 | Feature | Access matrix | middleware | High |

### M11 — Admin Stores `[P-DFD p7_store; L-DFD P5]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-STORE-F-01 | Feature | Index search + 4 sorts + pagination | browse | Low |
| ADM-STORE-F-02 | Feature | Store create: name ≥3; image optional (nullable) vs category's required; create with & without image | validation asymmetry | High |
| ADM-STORE-F-03 | Feature | Update name / replace image | branches | Medium |
| ADM-STORE-F-04 | Feature | Destroy → soft delete; **store_product rows survive** (no cascade on pivot for soft delete) — price pages then join a trashed store: document behavior via M2 test with trashed store | R5-adjacent | High |
| ADM-STORE-F-05 | Feature | Restore → deleted_at null | UC-sa2 | Medium |
| ADM-STORE-F-06 | Feature | Access matrix | middleware | High |

### M12 — Admin Users & Admin Profile `[P-DFD p8_user; UC-a1/sa1]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-USER-F-01 | Feature | Index: search fname/lname/email, role filter, all 6 sort options, pagination | browse | Medium |
| ADM-USER-F-02 | Feature | Store: valid → user created with role forced `admin`, hashed password, optional image; validation per rules incl. `role` must be `admin` (Rule::in) | sa1 manage admin | High |
| ADM-USER-F-03 | Feature | Update user incl. role change; **security spec (G8):** admin changing any role to `super-admin` — expected deny, documents current allow | R1 escalation | **Critical** |
| ADM-USER-F-04 | Feature | Destroy → soft delete; **user's contacts also soft-deleted** (model boot cascade); favorites/feedback handled by FK only on hard delete — document | R5 | High |
| ADM-USER-F-05 | Feature | Admin profile pages (adminProfile, EditAdminProfile, UpdateAdminProfile) → own data updated, image upload, validation | UC-a5 | Medium |
| ADM-USER-F-06 | Feature | updateAdminPassword: wrong current → error; valid → changed; min:8 | password parity with M5 | High |
| ADM-USER-F-07 | Feature | Restore user (super-admin) → deleted_at null; restore route as **admin** → 403 (super-admin middleware) | UC-sa2 boundary | **Critical** |
| ADM-USER-F-08 | Feature | Self-delete edge: admin deletes own account while logged in → document behavior (session vs soft-deleted auth) | edge | Medium |
| ADM-USER-F-09 | Feature | showRestore lists only trashed users, paginated | restore listing | Medium |
| ADM-USER-F-10 | Feature | Email duplication on update (no unique rule) → documents gap | G7-related | Low |

### M13 — Admin FAQs `[P-DFD p9_faq; L-DFD P5]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| ADM-FAQ-F-01 | Feature | Full resource cycle: create (question+answer required) → show → edit/update → index listing; soft delete; restore; showRestore list | standard P5 CRUD | High |
| ADM-FAQ-F-02 | Feature | Validation: missing question/answer rejected | validators | Medium |
| ADM-FAQ-F-03 | Feature | Index search via Scout (collection driver) | search branch | Low |
| ADM-FAQ-F-04 | Feature | Access matrix | middleware | High |
| ADM-FAQ-F-05 | Feature | Public `/FAQs` reflects admin CRUD (create → visible; delete → hidden; restore → visible again) — cross-module consistency | D3 read-after-write | Medium |

### M14 — Contacts `[P-DFD p2 & p10; L-DFD D-contacts; UC-g4/a3; DB contacts]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| CON-F-01 | Feature | **Per diagram (fails today, G1):** guest POST contact form → row in contacts (user_id null), success flash, redirect to contact page | UC-g4 intended flow | **Critical** (defect-capturing) |
| CON-F-02 | Feature | Validation matrix: name<3, bad email, mobile<9/non-numeric, message<10 or >5000 | exact validators | High |
| CON-F-03 | Feature | Boundary: message exactly 10 and exactly 5000 chars accepted; 9 and 5001 rejected | boundary | Medium |
| CON-F-04 | Feature | Logged-in user submit → user_id stored | D-contacts user link | Medium (post-G1 decision) |
| CON-F-05 | Feature | Current-state regression: guest POST to `/dashboard/contacts` → 403/redirect (locks in the accidental behavior so refactoring makes a conscious change) | as-built pinning | High |
| CON-F-06 | Feature | Admin index lists contacts paginated + Scout search; show displays message | UC-a3 | High |
| CON-F-07 | Feature | Admin destroy → soft delete; showRestore lists it; restore brings it back | UC-a3/sa2 | High |
| CON-F-08 | Feature | Deleting a user soft-deletes their contacts (model boot) | DB cascade | Medium |
| CON-F-09 | Feature | Access matrix on admin endpoints | middleware | High |

### M15 — Scraper Trigger `[P-DFD p11; UC-a2]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| SCRAP-F-01 | Feature | GET `/dashboard/scraper` → 200; `lastRun` = max scraped_at; `recentCount` = rows in last 24h (seed rows at boundary timestamps) | index stats | Medium |
| SCRAP-F-02 | Feature | POST `/scraper/run` → Artisan `scraper:run` invoked with/without `--store`; success exit → redirect with success flash; non-zero exit → error flash; exception → error flash. **Stub the exec'd python** (P-03) — assert command delegation & flash mapping only | controller branches | High |
| SCRAP-F-03 | Feature | Access matrix: guest/user blocked, admin allowed | middleware | High |
| SCRAP-C-01 | Console | `scraper:run` with missing scraper.py → exit 1 + error message (test via renamed path or base_path mock) | error branch | Medium |
| SCRAP-C-02 | Console | `scraper:run --store "X"` → built command contains escaped store arg; exit code passthrough from python (use a stub shell script as fake python) | command construction & injection-safety (escapeshellarg) | High |
| SCRAP-C-03 | Console | Store name with spaces/quotes → argument safely escaped (no shell splitting) | security | High |

### M16 — Python Scraper Microservice `[L-DFD-P6, D4; P-DFD p14–p17; SEQ-1]` (pytest)
**Unit — `BaseScraper.clean_price` (pure):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-U-01 | `'JD 25.99'` → 25.99; `'1,299.50'` → 1299.50; `'25'` → 25.0; `'Price: JD 10 JOD'` → 10.0 (first match) | regex extraction core of P6 | **Critical** |
| PY-U-02 | `None`/`''`/`'Out of stock'`/`'JD ,,'` → None | null/garbage branches | **Critical** |
| PY-U-03 | Config parsing: `price_selector` (single) merged ahead of `price_selectors`; defaults currency JOD, delay 3 | init branches | High |

**Unit — `StaticScraper`:**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-U-04 | `fetch_url`: mocked requests — success returns html; HTTP error ×3 → None; assert **3 attempts and backoff sleeps 1s,2s** (mock sleep) | SEQ-1 retry rnote | **Critical** |
| PY-U-05 | `run` with fixture HTML: first selector hit; fallback to second selector when first missing; no selector matched → save_to_db(url, None) path | selector fallback order | **Critical** |
| PY-U-06 | fetch returns None (all retries failed) → `failed` save for that product; loop **continues to next product** | SEQ-1 resilience | High |
| PY-U-07 | delay slept per product (mock sleep, assert call count) | politeness branch | Low |

**Unit — `DynamicScraper` (pytest-asyncio, mocked playwright page):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-U-08 | `fetch_price`: selector found → inner_text returned; combined-selector timeout → None; exception ×3 → None with backoff | dynamic retry/timeout branches | **Critical** |
| PY-U-09 | Empty price_selectors → returns None immediately (no wait) | guard branch | Medium |
| PY-U-10 | `run`: raw_text None → failed save; else cleaned save; browser closed even on product error | lifecycle | High |

**Unit — `BaseScraper.save_to_db`:**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-U-11 | price None → writer called with (…, 0, currency, 'failed') and returns False | failed-row contract per SEQ-1 | **Critical** |
| PY-U-12 | price valid → writer called with 'ok' | ok-row contract | **Critical** |

**Unit — `db/writer.insert_price_history` (mock connection/cursor):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-U-13 | sp_id lookup miss (no store_product for product+store_name) → returns False, **no INSERT executed** | orphan protection | **Critical** |
| PY-U-14 | ok insert → INSERT price_history executed AND **store_product.product_price UPDATE executed** AND product_url UPDATE when url provided | side-effect contract (P6→D4 + D2 sync) | **Critical** |
| PY-U-15 | failed status insert → price_history inserted, **no product_price update** | conditional sync branch | High |
| PY-U-16 | exception mid-way → returns False; connection closed in finally | cleanup contract | Medium |
| PY-U-17 | `get_connection`: missing/failed MySQL → None (mock connector raising) | error branch | Medium |

**Integration (optional, marked, needs test MySQL):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| PY-I-01 | Real insert → row present with correct sp_id; store_product price synced | end-to-end D4 write | Medium |
| PY-I-02 | `scraper.py --store "NoSuch"` → exit code 1 + error log (SEQ-1 config branch); full run with mocked scrapers → per-store isolation (one store raising doesn't stop others) | entry-point branches | Medium |

**End-to-end contract test (PHP↔Python):**
| ID | Specification | Why | Priority |
|---|---|---|---|
| SCRAP-E2E-01 | After admin attaches product URLs (ADM-PROD-F-10), regenerated `config.json` entries contain exactly the part_id/url pairs the Python `load_config` expects (validate against a JSON-schema-ish assertion) | the two processes meet at config.json (G-adjacent) | **Critical** |

### M17 — Authorization Cross-Cutting `[Use-case actor hierarchy]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| SEC-F-01 | Feature | **Full route×actor matrix test**: every `/dashboard/*` URL × {guest→login redirect, user→403, admin→200/redirect-as-designed, super-admin→200} | single test pins entire admin surface (sa3/a5 inheritance) | **Critical** |
| SEC-F-02 | Feature | Every `restore-*` route: admin → 403, super-admin → allowed | super-admin gate (UC-sa2) | **Critical** |
| SEC-F-03 | Feature | Auth-only routes (favorites, account, feedback, builder save/my-builds/delete): guest → login | auth gate | High |
| SEC-F-04 | Feature | Public routes reachable by all four actors | g1..g4 for all | Medium |

### M18 — Soft-Delete & Restore `[P-DFD p13; UC-sa2; DB SoftDeletes]`
| ID | Type | Specification | Why | Priority |
|---|---|---|---|---|
| REST-F-01..06 | Feature | Per entity (users, categories, stores, products, contacts, faqs): delete → `showRestore` lists it, normal index hides it; `restore-{x}/{id}` → deleted_at null, visible again, success flash | 6 parallel restore flows in p13 | **Critical** (one parametrized suite) |
| REST-F-07 | Feature | Restore non-trashed / unknown id → documents current `find()` null → fatal (expected 404; tag defect) | error case shared by all 6 controllers | High |
| REST-F-08 | Feature | Public surfaces reflect restores (category back in navbar queries, product back in listings, FAQ back on /FAQs) | cross-module | Medium |

---

## 9. Consolidated Case Matrix

### 9.1 Edge cases
- Product with no stores (MIN price null) — landing/category/builder/build-total/single-page.
- Product with no images; description not valid JSON; description `null`.
- Category with 0 products; unknown category id; `/category/0`.
- Single-page with only `failed` price rows; exactly one store; many stores same name (groupBy store_name collision — document).
- Pagination exact-boundary counts (7/15/25 items).
- Build with 1 part; 8 parts; duplicate part ids in payload; part from non-slot category.
- Compatibility: missing socket/form_factor/tdp on either side; string vs int TDP.
- Feedback rate boundaries 1/5; message 10/5000 chars on contact; mobile exactly 9 digits.
- Favorite toggle twice (state returns to empty); remove non-favorited product (detach no-op → still success JSON).
- Restore an entity twice; restore id that was hard-deleted.
- Upload: 0 files, 1 file, many files same second (`time()` name collision → overwrite — document).
- Scraper: product url `"#"` present in real config.json (invalid URL fetch behavior).
- price_history scraped_at exactly 24h ago (recentCount boundary `>=`).

### 9.2 Error cases
- All 404s: unknown product/category/build/faq/contact/user/image ids (note G4: single-page currently 500s).
- DB constraint violation: attach favorite to missing product; feedback with missing product/user.
- SQLite file missing for fetchSpecs; malformed JSON inside specs_json.
- config.json missing/invalid when scraper starts (exit 1); store name not found (exit 1).
- MySQL down for Python writer; store_product row missing for sp lookup.
- exec/python binary missing for `scraper:run`.
- 403s: every middleware boundary (SEC matrix).
- 419 CSRF on POST without token (sanity: one representative POST).

### 9.3 Validation cases (exact rule parity — pin current rules so refactoring can't silently change them)
- Register: fname/lname req+min3 · email req+email · mobile req+min9+numeric+unique · gender req · password req+min5+confirmed.
- Account update: same minus password · image nullable+image+mimes jpeg,png,jpg.
- Password change: current required · password min8+confirmed (both user & admin variants).
- Contact: name req+min3 · email · mobile min9+numeric · message req+min10+max5000.
- Category: name req+min3 · image **required** on create, nullable on update · mimes jpeg,png,jpg.
- Store: name req+min3 · image **nullable** even on create.
- Product: name req+max255 · description req · brand req · category exists · key/value/price/url arrays req · price.* numeric.
- Product update: key.*/value.* strings · price.*.*/url.*.* nullable · new_store_id.* exists:stores.
- ProductImage: images.* image mimes png,jpg,jpeg,webp.
- Feedback: message req · rate int 1–5 · product_id/user_id exist.
- FAQ: question/answer required.
- Build save: name req+max150 · notes nullable · part_ids req+min1+exists.
- check-compatibility: part_ids array, each integer+exists.
- User (admin) create: role must equal `admin`; password min5.

---

## 10. Missing Requirements / Documentation Gaps (to resolve BEFORE or DURING test implementation)

1. **G1 decision needed**: public contact submission — implement route or amend diagram (UC-g4). Tests exist for both readings (CON-F-01 vs CON-F-05).
2. **Diagram update needed**: PC Builder module (routes, builds/build_parts tables, products compatibility columns) absent from all diagrams (G2).
3. **Email uniqueness at registration** unspecified (G7); password policy weak (min5 vs min8 elsewhere) — inconsistency to confirm.
4. **Ownership rules** for feedback edit/delete and account update are undefined in docs; code allows IDOR (G5/G6). Product owner must confirm intended policy — tests specify the secure reading.
5. **Role-management authority**: UC-sa1 says Sup Admin manages admins; code lets any admin create/update roles incl. super-admin (G8). Confirm intended rule.
6. **StoreProduct model missing** though referenced by PriceHistory (G3) — decide: add model or fix relation; integration tests currently forced through query builder.
7. **Product-image delete flow broken** (G9) — confirm intended UX (which id, file deletion) before tests can assert "expected".
8. **Stock semantics**: `product_status` ('in stock'/'out of stock') never filters any query — is that intended for builder & comparison pages? (G15)
9. **No rate limiting/throttling** specified for contact form, feedback, login attempts (login has framework throttle via UI? — verify; contact/feedback have none).
10. **Scraper schedule ownership**: cron expression lives outside the app (`0 */6 * * *`); not represented or verifiable in Laravel — operational runbook item, not a code test.
11. **Debug route `/hi`** named `login` (G10) — removal decision pending; smoke test pins correct resolution meanwhile.
12. **No uniqueness** on favorite/store_product pairs (G12) — confirm whether duplicates are acceptable.
13. **AGENTS.md absent** and README minimal → testing conventions in this document should be promoted into contributor docs during refactoring.

---

## 11. Components That Should NOT Be Unit Tested
| Component | Reason |
|---|---|
| Laravel UI auth internals (`AuthenticatesUsers`, `RegistersUsers` traits, throttling internals) | Framework-tested; test only via feature flows (AUTH-F-*) and the `authenticated()` redirect override |
| Framework middleware (EncryptCookies, TrimStrings, VerifyCsrfToken, TrustProxies, ValidateSignature…) | Framework-owned; covered implicitly by feature tests |
| `HomeController`, stock `Controller`, Providers, Exception Handler | Scaffold pass-throughs; no business logic |
| Blade templates/markup | Assert view data & key content only, never HTML structure/CSS |
| `extractSocket` regex engine itself (PHP PCRE) | Test the service's observable warnings (BLD-U-03), not the regex in isolation beyond service outputs |
| `similar_text`/`PDO` in fetchSpecs | PHP native; test at the `getBestMatch` outcome level via fixture SQLite DBs |
| Playwright browser binary, Chromium rendering | External; mock the page API (PY-U-08..10). A single optional smoke marker may launch it locally, never in CI |
| Real store websites (mcc-jo etc.) | External & volatile; all HTTP mocked. Optional nightly live probe is ops monitoring, not a test |
| `mysql-connector-python` driver | Mock connection/cursor; real DB only in marked PY-I-* integration tests |
| Scout/Algolia driver internals | Use collection driver; test search *behavior* only (PUB-F-09) |
| `syncScraperConfig` JSON pretty-print formatting | Assert parsed structure/values, never byte-formatting |
| Factory/Seeder classes themselves | Test infrastructure, not subjects |
| Debug route `/hi` body | Only its name-collision effect (AUTH-F-08) |

---

## 12. Coverage Goals
| Scope | Target | Rationale |
|---|---|---|
| `app/Services/*` (BuildCompatibilityService) | **100% line / ≥95% branch** | pure logic, highest defect cost |
| `app/Http/Middleware/*` (custom 2) + `app/Policies/*` | **100%** | tiny, security-critical |
| Controllers M2 (UserSide), M8 (Product), M6 (Build) | **≥90% line** | core business flows P2/P5/builder |
| All remaining controllers | **≥80% line** | CRUD parity |
| Models (relations, boot cascades, searchable arrays) | every relation & boot hook exercised ≥1× | schema contract |
| Overall `app/` line coverage | **≥75%** (measured via phpunit `--coverage`; excludes views) | baseline before refactor |
| Python: `scrapers/*`, `db/writer.py`, `scraper.py` | **≥85% line** for clean_price/save_to_db/writer; ≥75% overall | scraper is data-integrity critical |
| Requirement coverage | **100% of use cases g1–sa2 mapped to ≥1 automated test** (traceability table §2.5 must have zero gaps) | diagram-derived guarantee |
| Every route in §1.5 | ≥1 feature test (status-level minimum) | no unmapped endpoint |

---

## 13. Test Execution Order
| Phase | Contents | Gate to proceed |
|---|---|---|
| **0 — Infra** | P-01..P-08 (test DB, Scout driver, factories, python venv+pytest, file/exec isolation) | `php artisan test` runs green with ExampleTest; `pytest` collects 0 errors |
| **1 — Pure units (fast, no DB)** | AUTH-U-01/02, BLD-U-01..11, ADM-PROD-U-01..04, PY-U-01..17 | all green |
| **2 — Auth & access** | AUTH-F-*, SEC-F-01..04 | all green (security specs that document *defects* are marked as expected-fail/incomplete with linked G-ids, NOT deleted) |
| **3 — Public read paths** | PUB-F-01..12, SEQ-2 assertions | green |
| **4 — User write paths** | FAV-F-*, FB-F-*, ACC-F-*, BLD-F-* | green |
| **5 — Admin CRUD** | DASH-F-*, ADM-PROD-*, ADM-IMG-*, ADM-CAT-*, ADM-STORE-*, ADM-USER-*, ADM-FAQ-*, CON-F-* | green |
| **6 — Restore & cascades** | REST-F-01..08 + cascade assertions from M10/M12 | green |
| **7 — Scraper chain** | SCRAP-F-*, SCRAP-C-*, SCRAP-E2E-01, PY-I-* (marked) | green |
| **8 — Full regression** | entire suite + coverage report vs §12 gates | coverage gates met; zero unmapped routes/use cases |

CI suggestion: unit phases on every commit; full suite on PR; PY-I-* and any live probes nightly only.

---

## 14. Implementation Checklist for the Coding Agent
> Execute strictly in order. Each box = one commit-sized unit. Do NOT modify application code while implementing tests; where a spec documents a defect (G-id), write it as an incomplete/expected-fail test with the G-id in its name/description and STOP — report back instead of fixing.

**Phase 0 — Infrastructure**
- [ ] 0.1 P-01: point phpunit testing env at sqlite `:memory:` (or `pc_tech_test`); prove `RefreshDatabase` migrates cleanly (note: price_history modify-migration runs raw SQL UPDATE — verify it passes on empty tables in the chosen driver; if sqlite-incompatible, use MySQL test DB and record the decision).
- [ ] 0.2 P-02: set `SCOUT_DRIVER=collection` for tests.
- [ ] 0.3 P-04/P-05: add `FeedbackFactory`, `BuildFactory`, `BuildPartFactory`, `PriceHistoryFactory`, pivot helper, and `UserFactory` role states (`user/admin/superAdmin`).
- [ ] 0.4 P-03: introduce test-safe isolation for `syncScraperConfig` (backup/restore config.json around the suite) and a stub executable for scraper console tests.
- [ ] 0.5 P-06: create `scraper/requirements-dev.txt` (pytest, pytest-asyncio, pytest-mock, responses) and `scraper/tests/` skeleton with `conftest.py` (fixture HTML pages, mock page objects). Do not alter scraper source.
- [ ] 0.6 P-08: upload tests use temp storage and clean up.

**Phase 1 — Unit suites**
- [ ] 1.1 `tests/Unit/MiddlewareTest` — AUTH-U-01, AUTH-U-02.
- [ ] 1.2 `tests/Unit/BuildCompatibilityServiceTest` — BLD-U-01..10 (uses in-memory model instances; mock `Product::with` query via relation preset — if Eloquent coupling prevents pure unit, implement as DB-backed unit and mark accordingly).
- [ ] 1.3 `tests/Unit/BuildPolicyTest` — BLD-U-11.
- [ ] 1.4 `tests/Unit/ProductSpecMappingTest` — ADM-PROD-U-01..04 (invoke private methods via reflection; acceptable for characterization pre-refactor).
- [ ] 1.5 `scraper/tests/test_clean_price.py` — PY-U-01..03.
- [ ] 1.6 `scraper/tests/test_static_scraper.py` — PY-U-04..07 (mock `requests.get`, `time.sleep`).
- [ ] 1.7 `scraper/tests/test_dynamic_scraper.py` — PY-U-08..10 (async mocks).
- [ ] 1.8 `scraper/tests/test_writer.py` — PY-U-11..17 (mock connection/cursor, assert call order & SQL params).

**Phase 2 — Auth & security**
- [ ] 2.1 `tests/Feature/Auth/AuthenticationTest` — AUTH-F-01..10 (Mail::fake for reset).
- [ ] 2.2 `tests/Feature/Security/AccessMatrixTest` — SEC-F-01..04 (data-provider over route list; keeps §1.5 enforced).
- [ ] 2.3 Defect specs: ACC-F-07 (G5), FB-F-06 (G6), ADM-USER-F-03 (G8), AUTH-F-03 (G7), AUTH-F-08 (G10) — mark incomplete with G-ids; report.

**Phase 3 — Public**
- [ ] 3.1 `tests/Feature/Public/LandingTest` — PUB-F-01/02/11.
- [ ] 3.2 `tests/Feature/Public/CategoryTest` — PUB-F-03/04/09/12.
- [ ] 3.3 `tests/Feature/Public/SinglePageTest` — PUB-F-05..08 (SEQ-2 assertions: latest-per-store, failed-excluded, chart grouping).
- [ ] 3.4 `tests/Feature/Public/StaticPagesTest` — PUB-F-10.

**Phase 4 — User features**
- [ ] 4.1 `tests/Feature/FavoriteTest` — FAV-F-01..07 + FAV-U-01 placement.
- [ ] 4.2 `tests/Feature/FeedbackTest` — FB-F-01..09.
- [ ] 4.3 `tests/Feature/AccountTest` — ACC-F-01..08.
- [ ] 4.4 `tests/Feature/BuilderTest` — BLD-F-01..10.

**Phase 5 — Admin**
- [ ] 5.1 `DashboardTest` — DASH-F-01..04.
- [ ] 5.2 `Admin/ProductCrudTest` — ADM-PROD-F-01..08.
- [ ] 5.3 `Admin/ProductSpecsTest` — ADM-PROD-F-09 (fixture sqlite DBs: missing file, no-match, each component type).
- [ ] 5.4 `Admin/ScraperConfigSyncTest` — ADM-PROD-F-10 + SCRAP-E2E-01.
- [ ] 5.5 `Admin/ProductImageTest` — ADM-IMG-F-01..06 (G9 spec → incomplete; report).
- [ ] 5.6 `Admin/CategoryTest` — ADM-CAT-F-01..06 (cascade + restore-asymmetry).
- [ ] 5.7 `Admin/StoreTest` — ADM-STORE-F-01..06 (incl. trashed-store visibility on public page).
- [ ] 5.8 `Admin/UserTest` — ADM-USER-F-01..10.
- [ ] 5.9 `Admin/FaqTest` — ADM-FAQ-F-01..05.
- [ ] 5.10 `Admin/ContactTest` — CON-F-02..09; CON-F-01 (G1) as incomplete; report.

**Phase 6 — Restore**
- [ ] 6.1 `tests/Feature/RestoreTest` — REST-F-01..08 parametrized over the 6 entities + super-admin gate + null-id defect spec (G-adjacent, expected-fail).

**Phase 7 — Scraper chain**
- [ ] 7.1 `tests/Feature/ScraperControllerTest` — SCRAP-F-01..03 (stub Artisan/exec).
- [ ] 7.2 `tests/Feature/RunScraperCommandTest` — SCRAP-C-01..03.
- [ ] 7.3 `scraper/tests/test_integration.py` — PY-I-01/02 (marked `integration`; skipped unless `--run-integration` and test MySQL present).

**Phase 8 — Closure**
- [ ] 8.1 Coverage report; verify §12 gates; attach report to PR.
- [ ] 8.2 Traceability audit: every use case g1–sa2 and every route in §1.5 has ≥1 test id; list any orphans.
- [ ] 8.3 File the defect register (G1–G15 with linked test ids) as the refactoring backlog; confirm diagrams update for G2.
- [ ] 8.4 Full suite green (with only the G-marked expected-fails pending product decisions).

---

*End of strategy. This document is the single source of truth for test implementation order, scope, and traceability. Any deviation discovered during implementation (new routes, new behavior) must be added here first.*
