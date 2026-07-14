<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Services\ActivityService;

class LogUserLogin
{
    public function handle(UserLoggedIn $event): void
    {
        app(ActivityService::class)->log('login', 'User logged in', $event->user->id);
    }
}
