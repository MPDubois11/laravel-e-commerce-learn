<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', [UserController::class, 'homePage'])->name('index');
Route::get('/all-products', [UserController::class, 'productsPage'])->name('allproducts');
Route::get('/product/{id}', [UserController::class, 'productPage'])->name('product');

Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/add-to-cart/{id}', [UserController::class, 'addToCart'])->middleware(['auth', 'verified'])->name('addtocart');
Route::get('/view-cart', [UserController::class, 'viewCart'])->middleware(['auth', 'verified'])->name('viewcart');
Route::get('/remove-from-cart/{id}', [UserController::class, 'removeFromCart'])->middleware(['auth', 'verified'])->name('removefromcart');
Route::get('/checkout', [UserController::class, 'checkoutPage'])->middleware(['auth', 'verified'])->name('checkout');
Route::post('/place-order', [UserController::class, 'placeOrder'])->middleware(['auth', 'verified'])->name('placeorder');
Route::get('/stripe/success/{order}', [UserController::class, 'stripeSuccess'])->middleware(['auth', 'verified'])->name('stripe.success');
Route::get('/stripe/cancel/{order}', [UserController::class, 'stripeCancel'])->middleware(['auth', 'verified'])->name('stripe.cancel');
Route::get('/thank-you/{orderId}', [UserController::class, 'thankYouPage'])->middleware(['auth', 'verified'])->name('thankyou');

Route::get('/download-invoice/{orderId}', [UserController::class, 'downloadInvoice'])->middleware(['auth', 'verified'])->name('downloadinvoice');

// Stripe Webhook (no auth, no CSRF - verified by signature)
Route::post('/stripe/webhook', [UserController::class, 'stripeWebhook'])->name('stripe.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'admin'])->name('admin.dashboard');
    Route::get('/add-category', [AdminController::class, 'addCategory'])->name('admin.addcategory');
    Route::post('/add-category', [AdminController::class, 'postAddCategory'])->name('admin.postaddcategory');
    Route::get('/view-categories', [AdminController::class, 'viewCategories'])->name('admin.viewcategories');
    Route::get('/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.deletecategory');
    Route::get('/update-category/{id}', [AdminController::class, 'updateCategory'])->name('admin.updatecategory');
    Route::post('/update-category/{id}', [AdminController::class, 'postUpdateCategory'])->name('admin.postupdatecategory');

    Route::get('/add-product', [AdminController::class, 'addProduct'])->name('admin.addproduct');
    Route::post('/add-product', [AdminController::class, 'postAddProduct'])->name('admin.postaddproduct');
    Route::get('/view-products', [AdminController::class, 'viewProducts'])->name('admin.viewproducts');
    Route::get('/delete-product/{id}', [AdminController::class, 'deleteProduct'])->name('admin.deleteproduct');
    Route::get('/update-product/{id}', [AdminController::class, 'updateProduct'])->name('admin.updateproduct');
    Route::post('/update-product/{id}', [AdminController::class, 'postUpdateProduct'])->name('admin.postupdateproduct');

    Route::any('/search-product', [AdminController::class, 'searchProduct'])->name('admin.seachproduct');

    Route::get('/view-orders', [AdminController::class, 'viewOrders'])->name('admin.vieworders');
    Route::get('/view-order/{id}', [AdminController::class, 'viewOrder'])->name('admin.vieworder');
    Route::patch('/update-order-status/{id}', [AdminController::class, 'updateOrderStatus'])->name('admin.updateorderstatus');
});

require __DIR__.'/auth.php';
