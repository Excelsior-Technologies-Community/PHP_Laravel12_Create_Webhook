<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = ['payload', 'source', 'event_type', 'status', 'retry_count', 'error_message'];

    protected $casts = [
        'payload' => 'array',
    ];

    // Scopes for filtering
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event_type', $event);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }
}
