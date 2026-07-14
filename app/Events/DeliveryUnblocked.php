<?php

namespace App\Events;

use App\Models\DeliveryRoute;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryUnblocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public DeliveryRoute $route;

    public function __construct(DeliveryRoute $route)
    {
        $this->route = $route;
    }
}
