<?php

use App\Models\ProductImage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\UserSideController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\PcBuildController;
use Illuminate\Support\Facades\Auth;




Route::get('/', [UserSideController::class,'landing'])->name('landing');

// ── PC Builder (public) ────────────────────────────────────────────────────
Route::get('/builder', [BuildController::class, 'index'])->name('builder.index');
Route::get('/builder/parts/{category}', [BuildController::class, 'partsPage'])->name('builder.parts');
Route::get('/builder/parts-api/{category}', [BuildController::class, 'getParts'])->name('builder.partsApi');
Route::post('/builder/check-compatibility', [BuildController::class, 'checkCompatibility'])->name('builder.compatibility');

Route::get('/category/{id}', [UserSideController::class,'category'])->name('category');
Route::get('/category', [UserSideController::class,'category'])->name('categoryNull');

Route::get('/single-page/{id}', [UserSideController::class,'singlePage'])->name('singlePage');

Route::get('/About', [UserSideController::class,'about'])->name('about');

Route::get('/Contact Us', [UserSideController::class,'contact'])->name('contact');

Route::get('/FAQs', [UserSideController::class,'faqs'])->name('faqs');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





Route::middleware(['auth'])->group(function () {

    // ── PC Builder (auth-required) ─────────────────────────────────────────
    Route::post('/builder/save', [BuildController::class, 'store'])->name('builder.save');
    Route::get('/builder/my-builds', [BuildController::class, 'myBuilds'])->name('builder.myBuilds');
    Route::delete('/builder/{build}', [BuildController::class, 'destroy'])->name('builder.destroy');

    // ── PC Builder: build item management API ──────────────────────────────
    Route::get('/builds/{build}', [PcBuildController::class, 'show'])->name('builds.show');
    Route::get('/builds/{build}/compatibility', [PcBuildController::class, 'compatibility'])->name('builds.compatibility');
    Route::post('/builds/{build}/items', [PcBuildController::class, 'addItem'])->name('builds.items.add');
    Route::patch('/builds/{build}/items/{product}', [PcBuildController::class, 'updateItem'])->name('builds.items.update');
    Route::delete('/builds/{build}/items/{product}', [PcBuildController::class, 'removeItem'])->name('builds.items.remove');

    Route::post('/favorite/{productId}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');
    Route::delete('/favorites/remove/{product}', [FavoriteController::class, 'removeFavorite'])->name('favorite.remove');
    Route::get('/favorites/list', [FavoriteController::class, 'listFavorites'])->name('favorite.list');




    Route::get('/User-Account', [UserSideController::class,'account'])->name('account');
    Route::put('/User-Account/password', [UserSideController::class,'updatePassword'])->name('updatePassword');
    Route::PUT('/User-Account/{user}', [UserSideController::class,'updateAccount'])->whereNumber('user')->name('updateAccount');
    Route::middleware(['admin-or-super-admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

        Route::get('dashboard/admin', [UserController::class , "adminProfile"])->name("admin.index");
        Route::put('dashboard/admin/password', [UserController::class , 'updateAdminPassword'])->name('admin.updatePassword');
        Route::PUT('dashboard/admin/{admin}/edit', [UserController::class , "UpdateAdminProfile"])->name("admin.UpdateEditProfile");
        Route::get('dashboard/admin/edit', [UserController::class , "EditAdminProfile"])->name("admin.editProfile");
    //^ ----------------------------------user route start-----------------------------------------
        Route::resource('dashboard/users', UserController::class);
        Route::delete('dashboard/users/{user}', [UserController::class , "destroy"])->name("users.destroy");
        Route::get('dashboard/restore-u', [UserController::class , "showRestore"])->name("users.showRestore")->middleware('super-admin');
        Route::get('dashboard/restore-u/{id}', [UserController::class , "restore"])->name("users.restore")->middleware('super-admin');
    //^ ----------------------------------user route end -----------------------------------------


    //^ ----------------------------------category route start-----------------------------------------
        Route::get('dashboard/categories', [CategoryController::class , "index"])->name("category.index");

        Route::post('dashboard/categories', [CategoryController::class , "store"])->name("category.store");

        Route::PUT('dashboard/categories/{category}', [CategoryController::class , "update"])->name("category.update");

        Route::delete('dashboard/categories/{category}', [CategoryController::class , "destroy"])->name("category.destroy");
        Route::get('dashboard/restore-c', [CategoryController::class , "showRestore"])->name("category.showRestore")->middleware('super-admin');
        Route::get('dashboard/restore-c/{id}', [CategoryController::class , "restore"])->name("category.restore")->middleware('super-admin');
    //^ ----------------------------------category route end-----------------------------------------

        //^ ----------------------------------store route start-----------------------------------------
        Route::get('dashboard/stores', [StoreController::class , "index"])->name("store.index");

        Route::post('dashboard/stores', [StoreController::class , "store"])->name("store.store");

        Route::PUT('dashboard/stores/{store}', [StoreController::class , "update"])->name("store.update");

        Route::delete('dashboard/stores/{store}', [StoreController::class , "destroy"])->name("store.destroy");
        Route::get('dashboard/restore-s', [StoreController::class , "showRestore"])->name("store.showRestore")->middleware('super-admin');
        Route::get('dashboard/restore-s/{id}', [StoreController::class , "restore"])->name("store.restore")->middleware('super-admin');
    //^ ----------------------------------store route end-----------------------------------------


    //^ ----------------------------------product route start-----------------------------------------
    Route::get('dashboard/products', [ProductController::class , "index"])->name("product.index");

    Route::get('dashboard/products/create', [ProductController::class , "create"])->name("product.create");
    Route::get('dashboard/products/fields/{category}', [ProductController::class , "fields"])->name("product.fields");
    Route::get('dashboard/products/autocomplete', [ProductController::class , "autocomplete"])->name("product.autocomplete");

    Route::post('dashboard/products', [ProductController::class , "store"])->name("product.store");

    Route::PUT('dashboard/products/{product}', [ProductController::class , "update"])->name("product.update");

    Route::get('dashboard/products/{product}/edit' ,[ProductController::class , 'edit'])->name('product.edit');

    Route::get('dashboard/products/{product}', [ProductController::class , "show"])->name("product.show");

    Route::delete('dashboard/products/{product}', [ProductController::class , "destroy"])->name("product.destroy");
    Route::get('dashboard/restore-p', [ProductController::class , "showRestore"])->name("product.showRestore")->middleware('super-admin');
    Route::get('dashboard/restore-p/{id}', [ProductController::class , "restore"])->name("product.restore")->middleware('super-admin');
    //^ ----------------------------------product route end-----------------------------------------

    //^ ----------------------------------Scraper route start-----------------------------------------
    Route::get('dashboard/scraper', [ScraperController::class, 'index'])->name('scraper.index');
    Route::post('dashboard/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');
    //^ ----------------------------------Scraper route end-----------------------------------------

    //^ ----------------------------------product Image route start-----------------------------------------
        Route::get('dashboard/product/{product}/upload', [ProductImageController::class, "index"])->name("product.upload.images");
        Route::post('dashboard/product/{product}/upload', [ProductImageController::class, 'store'])->name('product.store.images');
        Route::delete('dashboard/product/{product}/upload/delete', [ProductImageController::class, 'destroy'])->name('product.destroy.images');
    //^ ----------------------------------product Image route end-----------------------------------------


    //^ ----------------------------------contact route start-----------------------------------------
        Route::get('dashboard/contacts', [ContactController::class , "index"])->name("contact.index");

        Route::post('dashboard/contacts', [ContactController::class , "store"])->name("contact.store");

        Route::get('dashboard/contacts/{contact}', [ContactController::class , "show"])->name("contact.show");

        Route::delete('dashboard/contacts/{contact}', [ContactController::class , "destroy"])->name("contact.destroy");
        Route::get('dashboard/restore-co', [ContactController::class , "showRestore"])->name("contact.showRestore")->middleware('super-admin');
        Route::get('dashboard/restore-co/{id}', [ContactController::class , "restore"])->name("contact.restore")->middleware('super-admin');
    //^ ----------------------------------contact route end -----------------------------------------

    //^ ----------------------------------FAQS route start-----------------------------------------
        Route::get('dashboard/faqs', [FaqsController::class , "index"])->name("faq.index");

        Route::get('dashboard/faqs/create', [FaqsController::class , "create"])->name("faq.create");
        Route::post('dashboard/faqs', [FaqsController::class , "store"])->name("faq.store");

        Route::PUT('dashboard/faqs/{faq}', [FaqsController::class , "update"])->name("faq.update");
        Route::get('dashboard/faqs/{faq}/edit' ,[FaqsController::class , 'edit'])->name('faq.edit');

        Route::get('dashboard/faqs/{faq}', [FaqsController::class , "show"])->name("faq.show");

        Route::delete('dashboard/faqs/{faq}', [FaqsController::class , "destroy"])->name("faq.destroy");
        Route::get('dashboard/restore-f', [FaqsController::class , "showRestore"])->name("faq.showRestore")->middleware('super-admin');
        Route::get('dashboard/restore-f/{id}', [FaqsController::class , "restore"])->name("faq.restore")->middleware('super-admin');
    //^ ----------------------------------FAQS route end -----------------------------------------
    });

//^ ----------------------------------Feedback route start -----------------------------------------
    Route::resource('feedback', FeedbackController::class);
//^ ----------------------------------Feedback route end -----------------------------------------

});
