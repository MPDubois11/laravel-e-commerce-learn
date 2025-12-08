<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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
});

require __DIR__.'/auth.php';
