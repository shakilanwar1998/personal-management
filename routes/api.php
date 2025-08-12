<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Email API Routes
Route::prefix('email')->group(function () {
    Route::post('/send', [EmailController::class, 'sendEmail'])->name('email.send');
    Route::post('/send-html', [EmailController::class, 'sendHtmlEmail'])->name('email.send-html');
    Route::post('/send-auto', [EmailController::class, 'sendEmailAuto'])->name('email.send-auto');
    Route::get('/test-config', [EmailController::class, 'testConfiguration'])->name('email.test-config');
});
