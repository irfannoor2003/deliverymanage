<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualDelivery extends Model
{
    protected $fillable = [
        'delivery_boy_id',
        'title',
        'note',
        'delivery_date',
        'delivery_time',
        'total_km',
        'image_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'total_km' => 'decimal:2',
        ];
    }

    public function deliveryBoy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_boy_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
