<?php

namespace App\Notifications;

use App\Models\DeliveryRoute;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Delivery #' . $this->route->id . ' Blocked')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Delivery route #' . $this->route->id . ' has been blocked.')
            ->line('From: ' . $this->route->from_location)
            ->line('To: ' . $this->route->to_location)
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->route->status)))
            ->action('View Route', url('/deliveryboy/dashboard'))
            ->line('Please contact admin for further instructions.');
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
