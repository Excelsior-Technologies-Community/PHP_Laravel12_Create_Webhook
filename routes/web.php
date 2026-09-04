<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookDashboardController;
use App\Http\Controllers\WebhookAnalyticsController;

Route::get('/', function () {

    return redirect()->route(
        'webhook.dashboard'
    );
});

/*
|--------------------------------------------------------------------------
| Webhook Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('webhook')
    ->name('webhook.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                WebhookDashboardController::class,
                'index'
            ]
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Analytics
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/analytics',
            [
                WebhookAnalyticsController::class,
                'index'
            ]
        )->name('analytics');

        /*
        |--------------------------------------------------------------------------
        | CSV Export
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/export',
            [
                WebhookDashboardController::class,
                'export'
            ]
        )->name('export');

        /*
        |--------------------------------------------------------------------------
        | Delete All
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/delete-all',
            [
                WebhookDashboardController::class,
                'destroyAll'
            ]
        )->name('delete-all');

        /*
        |--------------------------------------------------------------------------
        | Show
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{id}',
            [
                WebhookDashboardController::class,
                'show'
            ]
        )->name('show');

        /*
        |--------------------------------------------------------------------------
        | Replay
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{id}/replay',
            [
                WebhookDashboardController::class,
                'replay'
            ]
        )->name('replay');

        /*
        |--------------------------------------------------------------------------
        | Retry Failed
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{id}/retry',
            [
                WebhookDashboardController::class,
                'retry'
            ]
        )->name('retry');

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{id}',
            [
                WebhookDashboardController::class,
                'destroy'
            ]
        )->name('destroy');
    });
