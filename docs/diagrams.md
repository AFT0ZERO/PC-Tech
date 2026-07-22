# pc-tech-diagrams
## DB Schema
```
// ==========================================================
// pc_tech schema — CTI (Class Table Inheritance) + PC Builder
// ==========================================================

// ---------- Core / unchanged tables ----------

Table categories {
  id bigint [pk, increment]
  name varchar(255) [not null]
  image varchar(255) [null]
  specs_table varchar(255) [null]
  open_db_name varchar(255) [null]
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table users {
  id bigint [pk, increment]
  fname varchar(255) [null]
  lname varchar(255) [null]
  email varchar(255) [unique, not null]
  email_verified_at timestamp [null]
  mobile varchar(255) [unique, default: '07', null]
  gender varchar(255) [null]
  role varchar(255) [default: 'user', null]
  image varchar(255) [null, default: 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y']
  password varchar(255) [not null]
  deleted_at timestamp [null]
  remember_token varchar(100) [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table stores {
  id bigint [pk, increment]
  name varchar(255) [not null]
  image varchar(255) [null]
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table contacts {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id, null]
  name varchar(255) [null]
  email varchar(255) [null]
  mobile varchar(255) [null]
  message text
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table faqs {
  id bigint [pk, increment]
  question varchar(255) [not null]
  answer text [null]
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

// ---------- Products (CTI parent) ----------

Table products {
  id bigint [pk, increment]
  category_id bigint [ref: > categories.id, not null]
  name varchar(255) [not null]
  description longtext [null]
  brand varchar(255) [null]
  power_draw_watts int [null, note: 'Aggregate rule: PSU.wattage >= SUM(power_draw_watts of all items)*1.2']
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table product_images {
  id bigint [pk, increment]
  product_id bigint [ref: > products.id, not null]
  image varchar(255) [not null]
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table store_product {
  id bigint [pk, increment]
  store_id bigint [ref: > stores.id, not null]
  product_id bigint [ref: > products.id, not null]
  product_price decimal(8,2) [null]
  product_url varchar(255) [null]
  product_status varchar(255) [default: 'in stock']
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table price_history {
  id bigint [pk, increment]
  sp_id bigint [ref: > store_product.id, not null]
  price decimal(10,2) [not null]
  currency varchar(10) [default: 'JOD']
  scraped_at timestamp [null]
  status varchar(20) [default: 'ok', note: 'enum: ok, failed']
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table store_scraper_configs {
  id bigint [pk, increment]
  store_id bigint [ref: > stores.id, not null]
  mode varchar(10) [default: 'static', note: 'enum: static, dynamic']
  delay int [default: 3]
  price_selectors json [not null]
  currency varchar(3) [default: 'JOD']
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table favorite {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id, not null]
  product_id bigint [ref: > products.id, not null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table feedback {
  id bigint [pk, increment]
  message text [null]
  rate tinyint [not null]
  product_id bigint [ref: > products.id, not null]
  user_id bigint [ref: > users.id, not null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

// ---------- CTI extension tables (one per buildable category) ----------
// كل جدول: product_id هو PK و FK بنفس الوقت (علاقة 1-1 مع products)
// الأعمدة الصريحة = أعمدة حرجة تدخل في قواعد التوافق

Table cpu_specs {
  product_id bigint [pk, ref: > products.id]
  socket varchar(50) [null, note: 'Direct Match: cpu.socket == motherboard.socket']
}

Table motherboard_specs {
  product_id bigint [pk, ref: > products.id]
  socket varchar(50) [null, note: 'Direct Match: == cpu.socket']
  supported_ram_type varchar(20) [null, note: 'Direct Match: == ram.type']
  ram_slots tinyint [null, note: 'Aggregate: COUNT(ram sticks) <= ram_slots']
  max_ram_capacity_gb int [null, note: 'Aggregate: SUM(ram.capacity_gb) <= max_ram_capacity_gb']
  form_factor varchar(30) [null, note: 'Set Membership: form_factor IN case.supported_form_factors']
}

Table ram_specs {
  product_id bigint [pk, ref: > products.id]
  type varchar(20) [null, note: 'Direct Match: == motherboard.supported_ram_type']
  capacity_gb int [null, note: 'Aggregate: SUM(capacity_gb) <= motherboard.max_ram_capacity_gb']
}

Table storage_specs {
  product_id bigint [pk, ref: > products.id]
  interface varchar(30) [null, note: 'Direct/Set Membership: e.g. NVMe/SATA vs motherboard supported interfaces']
  capacity_gb int [null]
}

Table gpu_specs {
  product_id bigint [pk, ref: > products.id]
  length_mm int [null, note: 'Dimensional: <= case.max_gpu_length_mm']
  vram_gb int [null]
}

Table psu_specs {
  product_id bigint [pk, ref: > products.id]
  wattage int [null, note: 'Aggregate: wattage >= SUM(products.power_draw_watts)*1.2']
}

Table case_specs {
  product_id bigint [pk, ref: > products.id]
  supported_form_factors json [null, note: 'Set Membership target: motherboard.form_factor IN this array']
  max_gpu_length_mm int [null, note: 'Dimensional: >= gpu.length_mm']
  max_cooler_height_mm int [null, note: 'Dimensional: >= cpu_cooler.height_mm']
}

Table cpu_cooler_specs {
  product_id bigint [pk, ref: > products.id]
  supported_sockets json [null, note: 'Set Membership: cpu.socket IN this array']
  height_mm int [null, note: 'Dimensional: <= case.max_cooler_height_mm']
}

// ---------- PC Builder feature ----------

Table build_slots {
  id bigint [pk, increment]
  category_id bigint [ref: > categories.id, not null]
  min_qty smallint [not null, default: 0]
  max_qty smallint [not null, note: 'e.g. cpu 1-1, ram 1-4, gpu 0-2, psu 1-1']
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table pc_builds {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id, null, note: 'nullable - guest builds saved via session first']
  name varchar(150) [null]
  is_public boolean [default: false]
  deleted_at timestamp [null]
  created_at timestamp [null]
  updated_at timestamp [null]
}

Table build_items {
  id bigint [pk, increment]
  build_id bigint [ref: > pc_builds.id, not null]
  product_id bigint [ref: > products.id, not null]
  quantity smallint [not null, default: 1]
  created_at timestamp [null]
  updated_at timestamp [null]
}

```

## Logical DFD
```
flowchart TD
    guest[Guest]
    user[User]
    admin[Admin]
    stores[Online stores]

    p1([1 <br> Manage authentication])
    p2([2 <br> Browse & compare])
    p3([3 <br> Manage favourites])
    p4([4 <br> Submit feedback])
    p5([5 <br> Manage the catalogue.])
    p6([6 <br> Collect prices])

    d1[(D1: user accounts)]
    d2[(D2: products & categories)]
    d3[(D3: favourites & feedback)]
    d4[(D4: price records)]

    user -. login .-> guest
    guest -- credentials --> p1
    p1 -- save user --> d1
    d1 -. user data .-> p1

    guest -- browse request --> p2
    p2 -- read products --> d2
    d2 -. product list .-> p2
    d4 -. price data .-> p2

    user -- add / remove --> p3
    p3 -- save favourite --> d3
    d3 -. list .-> p3

    user -- write review --> p4
    p4 -- store feedback --> d3

    admin -- manage --> p5
    p5 -- CRUD products --> d2
    d2 -. catalogue data .-> p5

    stores -- HTML prices --> p6
    p6 -- store prices --> d4
```

## Physical DFD
### Public routes
```
flowchart LR
    browser[Browser <br> Guest / User]
    view[Blade view <br> HTML response]

    p1(["1 <br> UserSideController <br> landing(), category() <br> singlePage(), faqs()"])
    p2(["2 <br> ContactController <br> store() <br> POST /Contact Us"])

    d1[(D1: pc_tech.products)]
    d2[(D2: pc_tech.categories)]
    d3[(D3: pc_tech.faqs)]
    d4[(D4: pc_tech.price_history)]
    d5[(D5: pc_tech.contacts)]

    browser -- "GET /, /category, /single-page, /FAQs" --> p1
    browser -- "POST /Contact Us" --> p2

    p1 -- "SELECT products, categories" --> d1
    p1 --> d2
    p1 --> d3
    p1 -- "SELECT price_history" --> d4
    p1 -. "Blade view (HTML)" .-> view

    p2 -- "INSERT contacts" --> d5
```

### Auth user routes
```
flowchart LR
    browser[Browser <br> Auth. User]

    p3(["3 <br> Auth::routes() <br> register(), login() <br> logout(), resetPassword()"])
    p4(["4 <br> FavoriteController <br> toggleFavorite() <br> listFavorites(), removeFavorite()"])
    p5(["5 <br> FeedbackController <br> store(), update(), destroy() <br> resource /feedback"])
    p6(["6 <br> UserSideController <br> account(), updateAccount() <br> updatePassword()"])

    d6[(D6: pc_tech.users)]
    d7[(D7: pc_tech.favorite)]
    d8[(D8: pc_tech.feedback)]

    browser -- "POST /login, POST /register" --> p3
    browser -- "POST /favorite/{productId}" --> p4
    browser -- "POST /feedback" --> p5
    browser -- "PUT /User-Account/{user}" --> p6

    p3 -- "INSERT/SELECT users" --> d6
    d6 -. "session token" .-> browser
    p4 -- "INSERT/DELETE favorite" --> d7
    d7 -. "JSON response" .-> p4
    p5 -- "INSERT/UPDATE feedback" --> d8
    p6 -- "UPDATE users" --> d6
```

### Admin routes
```
flowchart TD
    browser[Browser <br> Admin]

    p7_dash(["7 <br> DashboardController <br> Index() <br> GET /dashboard"])
    p8_prod(["8 <br> ProductController <br> Index(), create(), store() <br> show(), edit(), update(), destroy() <br> fetchSpecs()"])
    p9_img(["9 <br> ProductImageController <br> Index(), store(), destroy() <br> POST/DELETE /product/{id}/upload"])
    p9_cat(["9 <br> CategoryController <br> Index(), store() <br> update(), destroy()"])

    p7_store(["7 <br> StoreController <br> Index(), store() <br> update(), destroy()"])
    p8_user(["8 <br> UserController <br> resource CRUD <br> adminProfile(), EditAdminProfile() <br> UpdateAdminProfile(), updateAdminPassword()"])
    p9_faq(["9 <br> FaqsController <br> Index(), create(), store() <br> show(), edit(), update(), destroy()"])
    p10_contact(["10 <br> ContactController admin <br> Index(), show(), destroy() <br> GET/DELETE /dashboard/contacts"])
    p11_scrap(["11 <br> ScraperController <br> Index(), run() <br> shell_exec python scraper.py"])

    d_all[(D1-D7: All tables — aggregate stats)]
    d1[(D1: pc_tech.products)]
    d2[(D2: pc_tech.product_images)]
    d3[(D3: pc_tech.categories)]

    d4[(D4: pc_tech.stores)]
    d5[(D5: pc_tech.users)]
    d6[(D6: pc_tech.faqs)]
    d7[(D7: pc_tech.contacts)]
    d8[(D8: pc_tech.price_history)]

    browser -- "GET /dashboard" --> p7_dash
    browser -- "GET/POST/PUT/DELETE /products" --> p8_prod
    browser -- "POST/DELETE /product/{id}/upload" --> p9_img
    browser -- "GET/POST/PUT/DELETE /categories" --> p9_cat

    browser -- "GET/POST/PUT/DELETE /stores" --> p7_store
    browser -- "GET/POST/PUT/DELETE /users /admin" --> p8_user
    browser -- "GET/POST/PUT/DELETE /faqs" --> p9_faq
    browser -- "GET/DELETE /contacts" --> p10_contact
    browser -- "GET /scraper <br> POST /scraper/run" --> p11_scrap

    p7_dash -- "SELECT aggregate stats" --> d_all
    p8_prod -- "INSERT / UPDATE / DELETE products" --> d1
    p9_img -- "INSERT / DELETE product_images" --> d2
    p9_cat -- "INSERT / UPDATE / DELETE categories" --> d3

    p7_store -- "INSERT / UPDATE / DELETE stores" --> d4
    p8_user -- "INSERT / UPDATE / DELETE users" --> d5
    p9_faq -- "INSERT / UPDATE / DELETE faqs" --> d6
    p10_contact -- "SELECT contacts" --> d7
    p11_scrap -- "INSERT price_history <br> via shell_exec" --> d8
```

### Super admin restore
```
flowchart LR
    browser[Browser <br> Super Admin]

    p13(["13 <br> Restore controllers <br> showRestore(), restore() <br> Users / Categories / Stores / Products / Contacts / FAQs"])

    d11[(D11: SoftDeletes — deleted_at = NULL)]

    browser -- "GET restore-u/c/s/p/co/f/{id}" --> p13
    p13 -- "SET deleted_at = NULL" --> d11
    d11 -. "record restored" .-> p13
```

### Python price scraper
```
flowchart TD
    cron[Cron <br> 0 */6 * * *]
    websites[Store websites <br> os-jo / mcc-jo <br> citycenter / numberone]

    config[config.json <br> store URLs + selectors]
    logfile[scraper.log <br> run results + errors]

    p14(["14 <br> scraper.py entry point <br> loads config.json <br> iterates stores, retry x3"])
    p15(["15 <br> StaticScraper <br> requests + BeautifulSoup4 <br> CSS selector parse"])
    p16(["16 <br> DynamicScraper <br> Playwright async <br> headless browser + CSS selector"])
    p17(["17 <br> db/writer.py <br> mysql-connector-python <br> INSERT price_history row"])

    d4[(D4: pc_tech.price_history)]

    cron -- "python scraper.py" --> p14
    config -- "read store config" --> p14

    p14 -- "static mode" --> p15
    p14 -- "dynamic mode" --> p16

    p15 -- "GET product URL" --> websites
    websites -.-> p15 

    p16 -- "GET product URL (JS render)" --> websites
    websites -. "rendered HTML" .-> p16

    p15 -- "parsed price" --> p17
    p16 -- "parsed price" --> p17

    p17 -- "write run result + errors" --> logfile
    p17 -- "INSERT price_history row" --> d4
```

## Sequence Diagram
```
@startuml
!theme plain
hide footbox

participant "Corn" as corn
participant "Py Scraper" as scraper
participant "Store Sites" as sites
participant "Database" as db
participant "Laravel" as laravel
participant "Browser" as browser

== Scraper run ==

activate corn

corn -> scraper : Trigger
activate scraper

scraper -> sites : Load Config.josn
activate sites
scraper -> sites : GET Product URL
sites --> scraper : HTML Response
scraper -> sites : Parse Price Selector
deactivate sites

rnote over scraper : Retry

scraper -> db : Insert Price_History
activate db
db --> scraper : Ok / Failed Status
deactivate db

scraper -> sites : Write Scraper.log
activate sites
deactivate sites
deactivate scraper

== User views product page ==

browser -> laravel : Get /sngle-page/{id}
activate browser
activate laravel

laravel -> db : Select product
activate db
db --> laravel : Product Data

laravel -> db : Select price_history
db --> laravel : Prices Per Store
deactivate db

laravel --> browser : Render Blade View
deactivate laravel
deactivate browser

deactivate corn

@enduml
```

## Use Case

```
@startuml
left to right direction
skinparam packageStyle rectangle

' --- Guest Section ---
package "Guest" #d5e8d4 {
    actor "Guest" as guest
    
    usecase "Browse website and\nproducts" as g1
    usecase "Compare Store Prices" as g2
    usecase "Login / Register" as g3
    usecase "Submit Contact Form" as g4

    guest -- g1
    guest -- g2
    guest -- g3
    guest -- g4
}

' --- Login User Section ---
package "Login User" #dae8fc {
    actor "Login User" as login_user
    
    usecase "Manage Favourites" as lu1
    usecase "Rate Products" as lu2
    usecase "Submit Feedback" as lu3
    usecase "Manage Account" as lu4
    usecase "All Guest Capabilities" as lu5

    lu1 -- login_user
    lu2 -- login_user
    lu3 -- login_user
    lu4 -- login_user
    lu5 -- login_user
}

' --- Admin Section ---
package "Admin" #ffe6cc {
    actor "Admin" as admin
    
    usecase "Manage Products, Categories,\nStores and Users" as a1
    usecase "Trigger Scraper" as a2
    usecase "View Contact Msgs" as a3
    usecase "View Feedback" as a4
    usecase "All User Capabilities" as a5

    admin -- a1
    admin -- a2
    admin -- a3
    admin -- a4
    admin -- a5
}

' --- Sup Admin Section ---
package "Sup Admin" #f8cecc {
    actor "Sup Admin" as sup_admin
    
    usecase "Manage Admin" as sa1
    usecase "Restore Deleted records" as sa2
    usecase "All admin Capabilities" as sa3

    sa1 -- sup_admin
    sa2 -- sup_admin
    sa3 -- sup_admin
}

@enduml
```