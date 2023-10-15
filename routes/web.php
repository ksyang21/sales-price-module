<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
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
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
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
    Route::get('/driver/create', [DriverController::class, 'create'])->name('driver.create');
    Route::post('/driver', [DriverController::class, 'store'])->name('driver.store');
    Route::delete('/driver/{id}', [DriverController::class, 'destroy'])->name('driver.destroy');
    Route::get('/customer_management', [CustomerController::class, 'index'])->name('customer_management');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});
Route::get('/frontend_dashboard', [DriverController::class, 'index'])->name('frontend_dashboard');

require __DIR__ . '/auth.php';
