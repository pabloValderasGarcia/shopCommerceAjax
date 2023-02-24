<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Carbon\Carbon;

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

// STATIC PAGES
Route::get('/', function () {
    $latestProducts = Product::latest()->take(5)->get();
    return view('index', ['latestProducts' => $latestProducts]);
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});



// AJAX
Route::get('getDataProducts', [App\Http\Controllers\ShopController::class, 'getDataProducts'])->name('getDataProducts');
Route::get('getImagesProduct', [App\Http\Controllers\ShopController::class, 'getImagesProduct'])->name('getImagesProduct');
Route::get('checkProductInCart', [App\Http\Controllers\ShopController::class, 'checkProductInCart'])->name('checkProductInCart');

Route::post('createProduct', [App\Http\Controllers\ProductController::class, 'createProduct'])->name('createProduct');
Route::post('editProduct/{id}', [App\Http\Controllers\ProductController::class, 'editProduct'])->name('editProduct');
Route::delete('deleteProduct/{id}', [App\Http\Controllers\ProductController::class, 'deleteProduct'])->name('deleteProduct');



// AUTHENTICATION
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Category
    Route::resource('/shop/categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::delete('/deleteImage/{image}', [App\Http\Controllers\ProductController::class, 'deleteImage'])->name('deleteImage');
    Route::resource('/admin', App\Http\Controllers\Admin\AdminController::class);
});

// SHOP
Route::resource('/shop', App\Http\Controllers\ShopController::class);
Route::resource('/shop/products', App\Http\Controllers\ProductController::class);

Route::middleware(['auth'])->group(function () {
    Route::post('/addCart/{product}/{quantity}', [App\Http\Controllers\CartController::class, 'addCart'])->name('addCart');
    Route::resource('/cart', App\Http\Controllers\CartController::class)->except('addCart');
});

