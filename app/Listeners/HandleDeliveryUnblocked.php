<?php

namespace App\Listeners;

use App\Events\DeliveryUnblocked;
use App\Services\ActivityService;
use App\Services\NotificationService;

class HandleDeliveryUnblocked
{
    public function handle(DeliveryUnblocked $event): void
    {
        app(ActivityService::class)->log(
            'delivery_unblocked',
            "Delivery #{$event->route->id} unblocked by admin"
        );

        if ($event->route->deliveryBoy) {
            app(NotificationService::class)->notifyDeliveryUnblocked(
                $event->route->deliveryBoy,
                $event->route
            );
        }
    }
}
