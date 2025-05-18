<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RoomsController;
use App\Http\Middleware\AdminMiddleware as admin;
use App\Http\Middleware\UserMiddleware as user;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(admin::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('dashboard');

    Route::resource('rooms', RoomsController::class);
    Route::get('reservation', [ReservationsController::class, 'index'])->name('reservation.index');
    Route::delete('reservation-delete/{id}', [ReservationsController::class, 'destroy'])->name('reservation.destroy');

    Route::put('reservation/{id}', [ReservationsController::class, 'confirmReservation'])->name('reservation.confirm');
    Route::put('reservation-checkOut/{id}', [ReservationsController::class, 'checkOut'])->name('reservation.checkout');

    Route::get('pendapatan', [PDFController::class, 'pendapatan'])->name('admin.pendapatan');
});

Route::get('reservation-create', [ReservationsController::class, 'create'])->name('reservation.create');
Route::post('/reservation/check-rooms', [ReservationsController::class, 'checkAvailability'])->name('reservasi.cek');

Route::middleware(user::class)->group(function () {

    Route::get('/reservation/{room_id}', [ReservationsController::class, 'showReservationForm'])->name('reservasi.form')->middleware('auth');
    Route::post('/reservation/store', [ReservationsController::class, 'storeReservation'])->name('reservasi.store')->middleware('auth');
    Route::get('your-reservation', [ReservationsController::class, 'your_booking'])->name('user.reservation');
    Route::get('/generate-pdf', [PDFController::class, 'generatePDF'])->name('user.pdf');

    Route::put('reservation-update/{id}', [ReservationsController::class, 'update'])->name('reservation.update');
    Route::delete('reservation-delete/{id}', [ReservationsController::class, 'destroy'])->name('reservation.destroy');
});


Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('oauth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('oauth.google.callback');
