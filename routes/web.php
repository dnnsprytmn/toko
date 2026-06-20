<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController; 
use Illuminate\Support\Facades\Route;

// ===== FRONTEND ROUTES =====
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('product.add-to-cart');
Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
Route::get('/remove-from-cart/{id}', [ProductController::class, 'removeFromCart'])->name('product.remove-from-cart');
Route::get('/about', [ProductController::class, 'about'])->name('about');

// ===== CART ROUTES =====
Route::get('/cart/count', [ProductController::class, 'cartCount'])->name('cart.count');

// ===== TRACK ORDER ROUTES =====
Route::get('/track-order', [TrackOrderController::class, 'index'])->name('track.order');
Route::post('/track-order/search', [TrackOrderController::class, 'search'])->name('track.order.search');

// ===== CONTACT ROUTES =====
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send'); 

// ===== UPDATE QUANTITY ROUTE =====
Route::post('/update-cart/{id}', [ProductController::class, 'updateCart'])->name('update.cart');

// ===== SHOP FILTER ROUTES =====
Route::get('/shop/all', [ProductController::class, 'allProducts'])->name('shop.all');
Route::get('/shop/popular', [ProductController::class, 'popularProducts'])->name('shop.popular');
Route::get('/shop/new', [ProductController::class, 'newProducts'])->name('shop.new');
Route::get('/shop/sale', [ProductController::class, 'saleProducts'])->name('shop.sale');

// ===== NEW: SOLD OUT ROUTE =====
Route::get('/shop/soldout', [ProductController::class, 'soldOut'])->name('shop.soldout');

// ===== SHOP FILTER BY STOCK =====
Route::get('/shop/filter/{status}', [ProductController::class, 'filterByStock'])->name('shop.filter');

Route::get('/shop/category/{category}', [ProductController::class, 'categoryProducts'])->name('shop.category');

// ===== CHECKOUT ROUTES =====
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/invoice/{order}', [CheckoutController::class, 'invoice'])->name('invoice');

// ===== ADMIN AUTHENTICATION ROUTES =====
Route::prefix('admin')->name('admin.')->group(function () {
    // Login & Register (tanpa middleware)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ===== PROTECTED ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('/products', AdminProductController::class);
    Route::delete('/products/{product}/delete-image', [AdminProductController::class, 'deleteImage'])
        ->name('products.delete-image');
    Route::get('/search-products', [AdminProductController::class, 'search'])
        ->name('search.products');
    
    // Orders Management
    Route::resource('/orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    
    // Admins Management (hanya untuk role selain staff)
    Route::middleware('admin.role:admin')->group(function () {
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
    });

    // ===== PROFILE ROUTES =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ===== REVENUE ROUTES =====
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/detail', [RevenueController::class, 'detail'])->name('revenue.detail');

    // ===== TEST EMAIL ROUTE =====
    Route::get('/test-email', function() {
        $order = App\Models\Order::first();
        if ($order) {
            return new App\Mail\OrderConfirmationMail($order);
        }
        return 'No order found';
    });

    // ===== CUSTOMERS ROUTES =====
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/orders', [CustomerController::class, 'orders'])->name('customers.orders');

    // ===== REPORTS ROUTES =====
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
});