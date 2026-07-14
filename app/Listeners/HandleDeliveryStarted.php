<?php

namespace App\Listeners;

use App\Events\DeliveryStarted;
use App\Services\ActivityService;

class HandleDeliveryStarted
{
    public function handle(DeliveryStarted $event): void
    {
        app(ActivityService::class)->log(
            'delivery_started',
            "Delivery #{$event->route->id} started by {$event->route->deliveryBoy->name}"
        );
    }
}
