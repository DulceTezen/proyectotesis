<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReportController;

Route::get('link', function(){
	Artisan::call('storage:link');
});

Route::get('/', [WebController::class, 'index'])->name('index');
Route::get('shop', [WebController::class, 'shop'])->name('shop');
Route::get('product/{product}', [WebController::class, 'product'])->name('product');
//Route::get('cart', [WebController::class, 'cart'])->name('cart.index');
Route::get('cart', [WebController::class, 'cart'])->name('cart');
Route::get('about', [WebController::class, 'about'])->name('about');
Route::get('contact', [WebController::class, 'contact'])->name('contact');
Route::get('book', [WebController::class, 'book'])->name('book');
Route::post('book', [WebController::class, 'book_store'])->name('book_store');

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('login', [AuthController::class, 'check'])->name('auth.check');
Route::get('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('register', [AuthController::class, 'store'])->name('auth.store');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('sales/{sale}/pdf', [SaleController::class, 'pdf'])->name('sales.pdf');

Route::middleware(['auth:web'])->group(function(){
	Route::get('checkout', [WebController::class, 'checkout'])->name('checkout');
	Route::post('checkout', [WebController::class, 'finalize'])->name('finalize');
	Route::get('success', [WebController::class, 'success'])->name('success');
	Route::get('profile', [WebController::class, 'profile'])->name('profile');
	Route::post('profile', [WebController::class, 'update'])->name('update');
	Route::get('orders', [WebController::class, 'orders'])->name('orders');
	Route::post('upload-voucher', [WebController::class, 'uploadVoucher'])->name('upload.voucher');

});

Route::prefix('admin')->group(function(){

	Route::get('login', [AuthAdminController::class, 'login'])->name('auth.admin.login');
	Route::post('login', [AuthAdminController::class, 'check'])->name('auth.admin.check');
	Route::get('logout', [AuthAdminController::class, 'logout'])->name('auth.admin.logout');

	Route::middleware(['auth:admin'])->group(function(){

		Route::get('/', [WebController::class, 'dashboard'])->name('dashboard');
		
		Route::resource('categories', CategoryController::class);
		Route::resource('brands', BrandController::class);
		Route::resource('products', ProductController::class);
		Route::resource('clients', ClientController::class);
		Route::resource('sales', SaleController::class);
		Route::resource('users', UserController::class);
		Route::resource('books', BookController::class);

		Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
		Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

	});

});
