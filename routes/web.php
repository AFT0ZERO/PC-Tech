<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqsController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard.home');
    })->name('dashboard');
    Route::get('dashboard/admin', [UserController::class , "adminProfile"])->name("admin.index");
//^ ----------------------------------user route start-----------------------------------------
    Route::get('dashboard/users', [UserController::class , "index"])->name("user.index");

    Route::get('dashboard/users/create', [UserController::class , "create"])->name("user.create");
    Route::post('dashboard/users', [UserController::class , "store"])->name("user.store");

    Route::PUT('dashboard/users/{user}', [UserController::class , "update"])->name("user.update");
    Route::get('dashboard/users/{user}/edit' ,[UserController::class , 'edit'])->name('user.edit');

    Route::get('dashboard/users/{user}', [UserController::class , "show"])->name("user.show");

    Route::delete('dashboard/users/{user}', [UserController::class , "destroy"])->name("user.destroy");
//^ ----------------------------------user route end -----------------------------------------


//^ ----------------------------------category route start-----------------------------------------
    Route::get('/categories', [CategoryController::class , "index"])->name("category.index");

    Route::get('/categories/create', [CategoryController::class , "create"])->name(name: "category.create");

    Route::post('/categories', [CategoryController::class , "store"])->name("category.store");

    Route::PUT('/categories/{category}', [CategoryController::class , "update"])->name("category.update");

    Route::get('/categories/{category}/edit' ,[CategoryController::class , 'edit'])->name('category.edit');

    Route::get('/categories/{category}', [CategoryController::class , "show"])->name("category.show");

    Route::delete('/categories/{category}', [CategoryController::class , "destroy"])->name("category.destroy");
//^ ----------------------------------category route start-----------------------------------------


//^ ----------------------------------contact route start-----------------------------------------
    Route::get('dashboard/contacts', [ContactController::class , "index"])->name("contact.index");

    Route::post('dashboard/contacts', [ContactController::class , "store"])->name("contact.store");

    Route::get('dashboard/contacts/{contact}', [ContactController::class , "show"])->name("contact.show");

    Route::delete('dashboard/contacts/{contact}', [ContactController::class , "destroy"])->name("contact.destroy");
//^ ----------------------------------contact route end -----------------------------------------

//^ ----------------------------------FAQS route start-----------------------------------------
    Route::get('dashboard/faqs', [FaqsController::class , "index"])->name("faq.index");

    Route::get('dashboard/faqs/create', [FaqsController::class , "create"])->name("faq.create");
    Route::post('dashboard/faqs', [FaqsController::class , "store"])->name("faq.store");

    Route::PUT('dashboard/faqs/{faq}', [FaqsController::class , "update"])->name("faq.update");
    Route::get('dashboard/faqs/{faq}/edit' ,[FaqsController::class , 'edit'])->name('faq.edit');

    Route::get('dashboard/faqs/{faq}', [FaqsController::class , "show"])->name("faq.show");

    Route::delete('dashboard/faqs/{faq}', [FaqsController::class , "destroy"])->name("faq.destroy");
//^ ----------------------------------FAQS route end -----------------------------------------

});
