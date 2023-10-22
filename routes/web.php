<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['role:admin'])->group(function () {
    Route::get('/driver_management', [DriverController::class, 'index'])->name('driver_management');
    Route::get('/create-driver', [DriverController::class, 'create'])->name('driver.create');
    Route::get('/driver/{id}', [DriverController::class, 'show'])->name('driver.show');
    Route::get('/edit_driver/{id}', [DriverController::class, 'edit'])->name('driver.edit');
    Route::post('/driver', [DriverController::class, 'store'])->name('driver.store');
    Route::put('/driver/{id}', [DriverController::class, 'update'])->name('driver.update');
    Route::delete('/driver/{id}', [DriverController::class, 'destroy'])->name('driver.destroy');
    Route::get('/customer_management', [CustomerController::class, 'index'])->name('customer_management');
    Route::get('/create-customer', [CustomerController::class, 'create'])->name('customer.create');
    Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/edit_customer/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/create-product', [ProductController::class, 'create'])->name('product.create');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/edit_product/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/create-price/product/{product_id}', [PriceController::class, 'create'])->name('price.product.create');
    Route::get('/create-price/customer/{customer_id}', [PriceController::class, 'create'])->name('price.customer.create');
    Route::post('/price', [PriceController::class, 'store'])->name('price.store');
    Route::delete('/price/{id}', [PriceController::class, 'destroy'])->name('price.destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::post('/driver_customer', [\App\Http\Controllers\DriverCustomerController::class, 'store'])->name('driver_customer.create');
    Route::put('/driver_customer/{driverCustomer}', [\App\Http\Controllers\DriverCustomerController::class, 'update'])->name('driver_customer.update');
});
Route::middleware(['role:driver'])->group(function () {
    Route::get('/frontend_customer', [DriverController::class, 'listCustomers'])->name('frontend_customers');
    Route::get('/frontend_customer_details/{id}', [CustomerController::class, 'getCustomerDetails'])->name('frontend_customer_details');
    Route::get('/new_order/{customer}', [OrderController::class, 'create'])->name('order.create');
    Route::get('/frontend_orders', [DriverController::class, 'listOrders'])->name('frontend_orders');
    Route::post('/place_order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/confirm_order/{id}', [OrderController::class, 'confirmOrder'])->name('confirm_order');
    Route::put('/pay_order/{id}', [OrderController::class, 'payOrder'])->name('pay_order');
    Route::delete('/cancel_order/{id}', [OrderController::class, 'cancelOrder'])->name('cancel_order');
    Route::delete('/order_detail/{id}', [OrderDetailsController::class, 'destroy'])->name('remove_detail');
    Route::put('/order_detail', [OrderDetailsController::class, 'update'])->name('update_detail');
});

require __DIR__ . '/auth.php';
