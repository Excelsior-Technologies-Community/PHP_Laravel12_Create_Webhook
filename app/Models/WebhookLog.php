<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'payload',
        'source',
        'event_type',
        'webhook_id',
        'status',
        'retry_count',
        'error_message',
        'is_duplicate',
        'duplicate_of',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_duplicate' => 'boolean',
        'processed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByStatus($query, $status)
    {
        return $query->where(
            'status',
            $status
        );
    }

    public function scopeBySource($query, $source)
    {
        return $query->where(
            'source',
            $source
        );
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where(
            'event_type',
            $event
        );
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate(
            'created_at',
            $date
        );
    }

    public function scopeDuplicates($query)
    {
        return $query->where(
            'is_duplicate',
            true
        );
    }

    public function scopeOriginals($query)
    {
        return $query->where(
            'is_duplicate',
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function originalWebhook()
    {
        return $this->belongsTo(
            self::class,
            'duplicate_of'
        );
    }

    public function duplicates()
    {
        return $this->hasMany(
            self::class,
            'duplicate_of'
        );
    }
}