<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    // Fields allowed for mass assignment
    protected $fillable = ['payload'];

    // Automatically convert JSON to array
    protected $casts = [
        'payload' => 'array',
    ];
}
