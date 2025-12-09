<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Webhook Route
|--------------------------------------------------------------------------
|
| This route receives webhook POST requests from external services.
| It is protected using a secret key header.
|
*/

Route::post('/webhook', [WebhookController::class, 'handle']);
