<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookDashboardController;
use App\Http\Controllers\WebhookAnalyticsController;

Route::get('/', function () {
    return redirect()->route('webhook.dashboard');
});

/*
|--------------------------------------------------------------------------
| Webhook Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('webhook')
    ->name('webhook.')
    ->group(function () {

        Route::get(
            '/',
            [WebhookDashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/analytics',
            [WebhookAnalyticsController::class, 'index']
        )->name('analytics');

        Route::get(
            '/{id}',
            [WebhookDashboardController::class, 'show']
        )->name('show');

        Route::post(
            '/{id}/replay',
            [WebhookDashboardController::class, 'replay']
        )->name('replay');
    });