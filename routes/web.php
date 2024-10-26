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
use Illuminate\Support\Facades\Auth;




Route::get('/', [UserSideController::class,'landing'])->name('landing');

Route::get('/category/{id}', [UserSideController::class,'category'])->name('category');
Route::get('/category', [UserSideController::class,'category'])->name('categoryNull');

Route::get('/single-page/{id}', [UserSideController::class,'singlePage'])->name('singlePage');

Route::get('/About', [UserSideController::class,'about'])->name('about');

Route::get('/Contact Us', [UserSideController::class,'contact'])->name('contact');

Route::get('/FAQs', [UserSideController::class,'faqs'])->name('faqs');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard.home');
    })->name('dashboard');

    Route::get('dashboard/admin', [UserController::class , "adminProfile"])->name("admin.index");
    Route::PUT('dashboard/admin/{admin}/edit', [UserController::class , "UpdateAdminProfile"])->name("admin.UpdateEditProfile");
    Route::get('dashboard/admin/edit', [UserController::class , "EditAdminProfile"])->name("admin.editProfile");
//^ ----------------------------------user route start-----------------------------------------
    Route::get('dashboard/users', [UserController::class , "index"])->name("user.index");

    Route::get('dashboard/users/create', [UserController::class , "create"])->name("user.create");
    Route::post('dashboard/users', [UserController::class , "store"])->name("user.store");

    Route::PUT('dashboard/users/{user}', [UserController::class , "update"])->name("user.update");
    Route::get('dashboard/users/{user}/edit' ,[UserController::class , 'edit'])->name('user.edit');

    Route::get('dashboard/users/{user}', [UserController::class , "show"])->name("user.show");

    Route::delete('dashboard/users/{user}', [UserController::class , "destroy"])->name("user.destroy");
    Route::get('dashboard/restore-u', [UserController::class , "showRestore"])->name("user.showRestore")->middleware('super-admin');
    Route::get('dashboard/restore-u/{id}', [UserController::class , "restore"])->name("user.restore")->middleware('super-admin');
//^ ----------------------------------user route end -----------------------------------------


//^ ----------------------------------category route start-----------------------------------------
    Route::get('dashboard/categories', [CategoryController::class , "index"])->name("category.index");

    Route::get('dashboard/categories/create', [CategoryController::class , "create"])->name(name: "category.create");

    Route::post('dashboard/categories', [CategoryController::class , "store"])->name("category.store");

    Route::PUT('dashboard/categories/{category}', [CategoryController::class , "update"])->name("category.update");

    Route::get('dashboard/categories/{category}/edit' ,[CategoryController::class , 'edit'])->name('category.edit');

    Route::get('dashboard/categories/{category}', [CategoryController::class , "show"])->name("category.show");

    Route::delete('dashboard/categories/{category}', [CategoryController::class , "destroy"])->name("category.destroy");
    Route::get('dashboard/restore-c', [CategoryController::class , "showRestore"])->name("category.showRestore")->middleware('super-admin');
    Route::get('dashboard/restore-c/{id}', [CategoryController::class , "restore"])->name("category.restore")->middleware('super-admin');
//^ ----------------------------------category route start-----------------------------------------

    //^ ----------------------------------store route start-----------------------------------------
    Route::get('dashboard/stores', [StoreController::class , "index"])->name("store.index");

    Route::get('dashboard/stores/create', [StoreController::class , "create"])->name(name: "store.create");

    Route::post('dashboard/stores', [StoreController::class , "store"])->name("store.store");

    Route::PUT('dashboard/stores/{store}', [StoreController::class , "update"])->name("store.update");

    Route::get('dashboard/stores/{store}/edit' ,[StoreController::class , 'edit'])->name('store.edit');

    Route::get('dashboard/stores/{store}', [StoreController::class , "show"])->name("store.show");

    Route::delete('dashboard/stores/{store}', [StoreController::class , "destroy"])->name("store.destroy");
    Route::get('dashboard/restore-s', [StoreController::class , "showRestore"])->name("store.showRestore")->middleware('super-admin');
    Route::get('dashboard/restore-s/{id}', [StoreController::class , "restore"])->name("store.restore")->middleware('super-admin');
//^ ----------------------------------store route start-----------------------------------------


//^ ----------------------------------product route start-----------------------------------------
 Route::get('dashboard/products', [ProductController::class , "index"])->name("product.index");

 Route::get('dashboard/products/create', [ProductController::class , "create"])->name(name: "product.create");

 Route::post('dashboard/products', [ProductController::class , "store"])->name("product.store");

 Route::PUT('dashboard/products/{product}', [ProductController::class , "update"])->name("product.update");

 Route::get('dashboard/products/{product}/edit' ,[ProductController::class , 'edit'])->name('product.edit');

 Route::get('dashboard/products/{product}', [ProductController::class , "show"])->name("product.show");

 Route::delete('dashboard/products/{product}', [ProductController::class , "destroy"])->name("product.destroy");
 Route::get('dashboard/restore-p', [ProductController::class , "showRestore"])->name("product.showRestore")->middleware('super-admin');
 Route::get('dashboard/restore-p/{id}', [ProductController::class , "restore"])->name("product.restore")->middleware('super-admin');
//^ ----------------------------------product route start-----------------------------------------

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
