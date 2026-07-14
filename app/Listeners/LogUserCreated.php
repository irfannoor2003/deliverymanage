<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\ActivityService;
use App\Services\NotificationService;

class LogUserCreated
{
    public function handle(UserCreated $event): void
    {
        app(ActivityService::class)->log('user_created', "User '{$event->user->name}' created with role {$event->user->role}");
        app(NotificationService::class)->notifyUserCreated($event->user);
    }
}
