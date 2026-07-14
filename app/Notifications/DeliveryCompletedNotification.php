<?php

namespace App\Notifications;

use App\Models\DeliveryRoute;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Delivery #' . $this->route->id . ' Completed')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Delivery route #' . $this->route->id . ' has been completed by ' . $this->deliveryBoy->name)
            ->line('From: ' . $this->route->from_location)
            ->line('To: ' . $this->route->to_location)
            ->line('Total KM: ' . $this->route->total_km)
            ->action('View Report', url('/admin/reports'));
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
