<?php

namespace App\Listeners;

use App\Events\DeliveryCompleted;
use App\Services\ActivityService;
use App\Services\NotificationService;

class HandleDeliveryCompleted
{
    public function handle(DeliveryCompleted $event): void
    {
        app(ActivityService::class)->log(
            'delivery_completed',
            "Delivery #{$event->route->id} completed by {$event->route->deliveryBoy->name}"
        );

        if ($event->route->deliveryBoy) {
            app(NotificationService::class)->notifyDeliveryCompleted(
                $event->route->deliveryBoy,
                $event->route
            );
        }
    }
}
