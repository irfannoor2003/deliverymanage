<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    public User $createdUser;

    public function __construct(User $createdUser)
    {
        $this->createdUser = $createdUser;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New User Created: ' . $this->createdUser->name)
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new user has been created:')
            ->line('Name: ' . $this->createdUser->name)
            ->line('Role: ' . ucfirst(str_replace('_', ' ', $this->createdUser->role)))
            ->action('View Members', url('/admin/members'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->createdUser->id,
            'title' => __('messages.notif_user_created_title'),
            'message' => __('messages.notif_user_created_msg', [
                'name' => $this->createdUser->name,
                'role' => $this->createdUser->role,
            ]),
        ];
    }
}
