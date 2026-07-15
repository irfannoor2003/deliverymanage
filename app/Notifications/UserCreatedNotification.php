<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
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
        return ['database'];
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
