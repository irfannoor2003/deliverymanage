<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryRoute extends Model
{
    protected $fillable = [
        'delivery_boy_id',
        'from_location',
        'to_location',
        'total_km',
        'boc',
        'image_path',
        'images',
        'audio_path',
        'status',
        'is_manual',
        'blocked_at',
        'unblocked_at',
        'started_at',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
    ];

    protected function casts(): array
    {
        return [
            'total_km' => 'decimal:2',
            'images' => 'array',
            'is_manual' => 'boolean',
            'blocked_at' => 'datetime',
            'unblocked_at' => 'datetime',
            'started_at' => 'datetime',
            'start_lat' => 'decimal:7',
            'start_lng' => 'decimal:7',
            'end_lat' => 'decimal:7',
            'end_lng' => 'decimal:7',
        ];
    }

    public function deliveryBoy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_boy_id');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(RouteStop::class)->orderBy('order');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('delivery_boy_id', $user->id);
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'in_progress'
            && $this->created_at->isToday()
            && now()->greaterThanOrEqualTo(now()->setHour(20)->setMinute(0)->setSecond(0));
    }
}
