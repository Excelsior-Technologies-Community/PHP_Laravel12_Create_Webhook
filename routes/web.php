<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookDashboardController;

Route::get('/', function () {
    return redirect()->route('webhook.dashboard');
});

// Admin Dashboard
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::get('/',             [WebhookDashboardController::class, 'index'])->name('dashboard');
    Route::get('/{id}',         [WebhookDashboardController::class, 'show'])->name('show');
    Route::post('/{id}/replay', [WebhookDashboardController::class, 'replay'])->name('replay');
});
