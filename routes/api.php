<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebhookLogController;

// Multi-source webhook endpoints
Route::post('/webhook',             [WebhookController::class, 'handle']);                      // general
Route::post('/webhook/{source}',    [WebhookController::class, 'handle']);                      // stripe, razorpay, whatsapp

// Webhook Log API
Route::get('/webhook-logs',                     [WebhookLogController::class, 'index']);        // list + filter
Route::post('/webhook-logs/{id}/replay',        [WebhookLogController::class, 'replay']);       // replay
