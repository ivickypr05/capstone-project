<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('profile');
});

// Authentication -----------------------------------------------------------------------------------
Auth::routes();

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');


// Admin --------------------------------------------------------------------------------------------
Route::get('/admin', [AdminController::class, 'index']);

// CRUD Category
Route::get('/category', [CategoryController::class, 'index']);
// Add Category
Route::get('/category/add', [CategoryController::class, 'create']);
Route::post('/category', [CategoryController::class, 'store']);
// Edit Category
Route::get('/category/{id}/edit', [CategoryController::class, 'edit']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
// Delete Category
Route::get('/category/{id}/delete', [CategoryController::class, 'destroy']);

// CRUD Product
Route::get('/product', [ProductController::class, 'index'])->name('product');
 // Add Product
 Route::get('/product/add', [ProductController::class, 'create']);
 Route::post('/product', [ProductController::class, 'store']);
