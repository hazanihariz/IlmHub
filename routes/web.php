<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicEventController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;

// Replace the default '/' route with this:
Route::get('/', [PublicEventController::class, 'index'])->name('home');

// Show Single Event Details (only when {id} is numeric, so /events/create still works)
Route::get('/events/{id}', [PublicEventController::class, 'show'])
    ->whereNumber('id')
    ->name('events.show');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// The "auth" middleware ensures only logged-in users can hit these URLs
Route::middleware(['auth'])->group(function () {
    // Events - create & manage own events
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/save', [EventController::class, 'store'])->name('events.store');
    Route::get('/events', function () {
        return redirect()->route('events.create');
    });

    Route::get('/my-events', [EventController::class, 'index'])->name('events.mine');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Bookings
    Route::post('/events/{id}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});
