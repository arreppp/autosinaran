<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public booking flow
Route::get('/', [BookingController::class, 'index'])->name('home');

Route::prefix('book')->name('booking.')->group(function () {
    Route::get('/packages',           [BookingController::class, 'packages'])->name('packages');
    Route::get('/date/{package}',     [BookingController::class, 'date'])->name('date');
    Route::post('/store',             [BookingController::class, 'store'])->name('store');
    Route::get('/payment/{booking}',  [PaymentController::class, 'show'])->name('payment');
    Route::post('/payment/{booking}', [PaymentController::class, 'upload'])->name('payment.upload');
    Route::get('/confirm/{booking}',  [BookingController::class, 'confirm'])->name('confirm');
});

Route::get('/api/availability/{package}', [BookingController::class, 'availability'])
    ->name('api.availability');

// Admin panel
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bookings', Admin\BookingController::class)->only(['index', 'show', 'destroy']);
    Route::patch('bookings/{booking}/approve', [Admin\BookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('bookings/{booking}/reject',  [Admin\BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('bookings/{booking}/proof',     [Admin\BookingController::class, 'proof'])->name('bookings.proof');

    Route::resource('packages',     Admin\PackageController::class)->except(['show']);
    Route::resource('blocked-dates', Admin\BlockedDateController::class)->only(['index', 'store', 'destroy']);
});

// Auth profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
