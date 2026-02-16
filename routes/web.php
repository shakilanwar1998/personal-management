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

// Invoice routes (accessible from Filament admin panel)
Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])
    ->name('invoices.download')
    ->middleware('auth');
Route::get('/invoices/{invoice}/view', [\App\Http\Controllers\InvoiceController::class, 'view'])
    ->name('invoices.view')
    ->middleware('auth');
