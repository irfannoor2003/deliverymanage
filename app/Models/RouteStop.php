<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteStop extends Model
{
    protected $fillable = [
        'delivery_route_id',
        'stop_name',
        'stop_km',
        'image_path',
        'images',
        'audio_path',
        'order',
        'lat',
        'lng',
    ];

    protected function casts(): array
    {
        return [
            'stop_km' => 'decimal:2',
            'images' => 'array',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function deliveryRoute(): BelongsTo
    {
        return $this->belongsTo(DeliveryRoute::class);
    }
}
