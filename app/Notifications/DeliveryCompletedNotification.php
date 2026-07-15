<?php

namespace App\Notifications;

use App\Models\DeliveryRoute;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeliveryCompletedNotification extends Notification
{
    use Queueable;

    public DeliveryRoute $route;
    public User $deliveryBoy;

    public function __construct(DeliveryRoute $route, User $deliveryBoy)
    {
        $this->route = $route;
        $this->deliveryBoy = $deliveryBoy;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'route_id' => $this->route->id,
            'title' => __('messages.notif_completed_title'),
            'message' => __('messages.notif_completed_msg', [
                'name' => $this->deliveryBoy->name,
                'id' => $this->route->id,
                'from' => $this->route->from_location,
                'to' => $this->route->to_location,
            ]),
        ];
    }
}
