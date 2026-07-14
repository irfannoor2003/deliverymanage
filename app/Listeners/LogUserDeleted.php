<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use App\Services\ActivityService;

class LogUserDeleted
{
    public function handle(UserDeleted $event): void
    {
        app(ActivityService::class)->log('user_deleted', "User '{$event->user->name}' deleted");
    }
}
