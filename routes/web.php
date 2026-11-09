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

// Public portfolio (standalone Vue app in resources/portfolio)
Route::view('/', 'portfolio')->name('portfolio');
Route::view('/projects', 'portfolio')->name('projects');
// CV hidden until the new format is ready; restore with: Route::view('/cv', 'portfolio')->name('cv');
Route::redirect('/cv', '/', 302);
Route::redirect('/about', '/#about', 301);
Route::redirect('/contact', '/#contact', 301);

// Public route to store visitor IP and location
Route::get('/payments', function () {
    return view('payments');
})->name('payments');
Route::post('/payments', [VisitorLogController::class, 'store'])->name('payments.store');

// Invoice preview / PDF (opened from the admin panel)
Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])
    ->name('invoices.download')
    ->middleware('auth');
Route::get('/invoices/{invoice}/view', [\App\Http\Controllers\InvoiceController::class, 'view'])
    ->name('invoices.view')
    ->middleware('auth');

// Public case studies: rendered by the portfolio app (resources/portfolio/cases/<slug>.js)
Route::view('/case-studies/{slug}', 'portfolio')
    ->where('slug', '[a-z0-9-]+')
    ->name('case-studies.show');

// Admin panel
Route::prefix('admin')
    ->middleware(\App\Http\Middleware\HandleAdminInertiaRequests::class)
    ->group(base_path('routes/admin.php'));
