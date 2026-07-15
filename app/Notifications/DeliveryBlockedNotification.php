<?php

namespace App\Notifications;

use App\Models\DeliveryRoute;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeliveryBlockedNotification extends Notification
{
    use Queueable;

    public DeliveryRoute $route;

    public function __construct(DeliveryRoute $route)
    {
        $this->route = $route;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'route_id' => $this->route->id,
            'title' => __('messages.notif_blocked_title'),
            'message' => __('messages.notif_blocked_msg', [
                'id' => $this->route->id,
                'from' => $this->route->from_location,
                'to' => $this->route->to_location,
            ]),
        ];
    }
}
