<?php

namespace App\Notifications;

use App\Models\DeliveryRoute;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryUnblockedNotification extends Notification
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
            ->subject('Delivery #' . $this->route->id . ' Unblocked')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Delivery route #' . $this->route->id . ' has been unblocked.')
            ->line('From: ' . $this->route->from_location)
            ->line('To: ' . $this->route->to_location)
            ->action('View Route', url('/deliveryboy/dashboard'))
            ->line('You can now continue with your delivery.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'route_id' => $this->route->id,
            'title' => __('messages.notif_unblocked_title'),
            'message' => __('messages.notif_unblocked_msg', [
                'id' => $this->route->id,
                'from' => $this->route->from_location,
                'to' => $this->route->to_location,
            ]),
        ];
    }
}
