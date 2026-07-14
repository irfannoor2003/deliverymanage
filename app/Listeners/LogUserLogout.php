<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use App\Services\ActivityService;

class LogUserLogout
{
    public function handle(UserLoggedOut $event): void
    {
        app(ActivityService::class)->log('logout', 'User logged out', $event->user->id);
    }
}
