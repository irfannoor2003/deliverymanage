<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\DeliveryBlockedNotification;
use App\Notifications\DeliveryUnblockedNotification;
use App\Notifications\DeliveryCompletedNotification;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyDeliveryBlocked(User $deliveryBoy, $route): void
    {
        $adminsAndSales = User::whereIn('role', ['admin', 'saleshead'])->get();
        $notification = new DeliveryBlockedNotification($route);

        Notification::send($deliveryBoy, $notification);
        Notification::send($adminsAndSales, $notification);
    }

    public function notifyDeliveryUnblocked(User $deliveryBoy, $route): void
    {
        $adminsAndSales = User::whereIn('role', ['admin', 'saleshead'])->get();
        $notification = new DeliveryUnblockedNotification($route);

        Notification::send($deliveryBoy, $notification);
        Notification::send($adminsAndSales, $notification);
    }

    public function notifyDeliveryCompleted(User $deliveryBoy, $route): void
    {
        $adminsAndSales = User::whereIn('role', ['admin', 'saleshead'])->get();
        Notification::send($adminsAndSales, new DeliveryCompletedNotification($route, $deliveryBoy));
    }

    public function notifyUserCreated(User $user): void
    {
        $adminsAndSales = User::whereIn('role', ['admin', 'saleshead'])->get();
        Notification::send($adminsAndSales, new UserCreatedNotification($user));
    }
}
