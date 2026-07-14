<?php

namespace App\Providers;

use App\Events\DeliveryBlocked;
use App\Events\DeliveryCompleted;
use App\Events\DeliveryStarted;
use App\Events\DeliveryUnblocked;
use App\Events\MessageSent;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Listeners\HandleDeliveryBlocked;
use App\Listeners\HandleDeliveryCompleted;
use App\Listeners\HandleDeliveryStarted;
use App\Listeners\HandleDeliveryUnblocked;
use App\Listeners\HandleMessageSent;
use App\Listeners\LogUserCreated;
use App\Listeners\LogUserDeleted;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserLoggedIn::class => [LogUserLogin::class],
        UserLoggedOut::class => [LogUserLogout::class],
        UserCreated::class => [LogUserCreated::class],
        UserDeleted::class => [LogUserDeleted::class],
        DeliveryStarted::class => [HandleDeliveryStarted::class],
        DeliveryCompleted::class => [HandleDeliveryCompleted::class],
        DeliveryBlocked::class => [HandleDeliveryBlocked::class],
        DeliveryUnblocked::class => [HandleDeliveryUnblocked::class],
        MessageSent::class => [HandleMessageSent::class],
    ];
}
