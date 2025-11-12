<?php

use App\Http\Controllers\VisitorLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return inertia('Welcome');
});

Route::get('/about', function () {
    return inertia('About');
});

Route::get('/contact', function () {
    return inertia('Contact');
});

// Public route to store visitor IP and location
Route::get('/payments', function () {
    return view('payments');
})->name('payments');
Route::post('/payments', [VisitorLogController::class, 'store'])->name('payments.store');

