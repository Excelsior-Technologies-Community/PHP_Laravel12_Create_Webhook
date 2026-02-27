# PHP_Laravel12_Create_Webhook

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-f72c1f?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/Webhook-Receiver-blue?style=for-the-badge" />
  <img src="https://img.shields.io/badge/Advanced-Security-success?style=for-the-badge" />
</p>

---

##  Overview  
This Laravel 12 project demonstrates how to build a **secure webhook endpoint** that receives JSON data from external services like Stripe, Razorpay, WhatsApp API, Firebase, ShipRocket, etc.

The system includes:  
✔ Secret-key authentication  
✔ Payload logging  
✔ JSON database storage  
✔ API-based routing  
✔ Postman testing support  
✔ Troubleshooting guide  

---

#  Features  
- Secure Secret Key Validation  
- Store webhook payload into database  
- Log complete payload for debugging  
- API route-based architecture  
- JSON casting using Eloquent  
- Works with all external webhook senders  
- Laravel 12 routing support enabled  

---

#  Folder Structure  
```
app/
│── Http/
│   └── Controllers/
│       └── WebhookController.php
│
│── Models/
│   └── WebhookLog.php
│
bootstrap/
└── app.php         # API routes enabled

config/
└── services.php    # Webhook secret mapping

database/
└── migrations/
    └── create_webhook_logs_table.php

routes/
└── api.php

.env
README.md
```

---

#  Step 1 — Install Laravel  
```bash
composer create-project laravel/laravel webhook-app
```

---

#  Step 2 — Setup ENV Variables  

Add database + webhook secret key:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhook
DB_USERNAME=root
DB_PASSWORD=

WEBHOOK_SECRET=D2QuHpxAiPuRYY95pwnRPUGTlohshCYTu75DCwDe
```

✔ Secret key MUST match the header sent by webhook provider.

---

#  Step 3 — Enable API Routes (Laravel 12 IMPORTANT)  

 **bootstrap/app.php**

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

 If you skip this step → `/api/webhook` route will not load.

---

#  Step 4 — Create Webhook Route  

 **routes/api.php**

```php
use App\Http\Controllers\WebhookController;

Route::post('/webhook', [WebhookController::class, 'handle']);
```

Webhook URL becomes:

```
POST http://127.0.0.1:8000/api/webhook
```

---

#  Step 5 — Create Webhook Controller  

 **app/Http/Controllers/WebhookController.php**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WebhookLog;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.webhook_secret');

        // Secret Key Validation
        if ($request->header('X-Webhook-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Log Payload
        Log::info('Webhook received:', $request->all());

        // Store into DB
        WebhookLog::create([
            'payload' => $request->all(),
        ]);

        return response()->json(['status' => 'success']);
    }
}
```

✔ Logs payload  
✔ Validates secret  
✔ Saves JSON to DB  

---

#  Step 6 — Add Webhook Secret Config  

 **config/services.php**

```php
'webhook_secret' => env('WEBHOOK_SECRET'),
```

---

#  Step 7 — Create Model & Migration  

### Create model:
```bash
php artisan make:model WebhookLog -m
```

---

 **database/migrations/create_webhook_logs_table.php**

```php
Schema::create('webhook_logs', function (Blueprint $table) {
    $table->id();
    $table->json('payload'); // store full JSON
    $table->timestamps();
});
```

---

 **app/Models/WebhookLog.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = ['payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
```

Run migration:

```bash
php artisan migrate
```

---

#  Step 8 — Testing Webhook with Postman  

### URL  
```
POST http://127.0.0.1:8000/api/webhook
```

### Required Headers:
| Header Name       | Value |
|------------------|--------|
| X-Webhook-Secret | Your secret key |
| Content-Type     | application/json |

### Sample Body:
```json
{
  "event": "order.created",
  "order_id": 151,
  "status": "paid"
}
```

Expected Response:
```json
{
  "status": "success"
}
```

---

#  Where Is Webhook Data Saved?

### ✔ Log file  
Location:  
`storage/logs/laravel.log`

### ✔ Database  
Table:  
`webhook_logs`

Payload saved exactly as JSON.

---

#  Troubleshooting Guide  

###  403 Unauthorized  
Reason: Secret header mismatch  
Fix:
```bash
php artisan config:clear
```

---

###  Route Not Found  
Reason: API routing not enabled  
Fix: Update `bootstrap/app.php`

---

###  Payload not stored  
Reason: Migration not run  
Fix:
```bash
php artisan migrate
```

---

#  Final Result  

✔ Secure webhook receiver ready  
✔ JSON stored + logged  
✔ Perfect for payment gateways, shipping APIs, OTP services, etc.  


###SCREENSHOTS:-

<img width="1795" height="634" alt="Screenshot 2025-12-09 165649" src="https://github.com/user-attachments/assets/f236de43-29e4-475e-8f33-0824e21c7837" />


<img width="894" height="279" alt="Screenshot 2025-12-09 172257" src="https://github.com/user-attachments/assets/454d56aa-e4b3-4074-a0a4-ade60c9e9843" />



