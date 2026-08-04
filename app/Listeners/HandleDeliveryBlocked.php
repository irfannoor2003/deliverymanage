<?php

namespace App\Listeners;

use App\Events\DeliveryBlocked;
use App\Services\ActivityService;
use App\Services\NotificationService;

class HandleDeliveryBlocked
{
    public function handle(DeliveryBlocked $event): void
    {
        app(ActivityService::class)->log(
            'delivery_blocked',
            "Delivery #{$event->route->id} blocked (overdue)",
            $event->route->delivery_boy_id
        );

        if ($event->route->deliveryBoy) {
            app(NotificationService::class)->notifyDeliveryBlocked(
                $event->route->deliveryBoy,
                $event->route
            );
        }
    }
}
