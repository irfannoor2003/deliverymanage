<?php

namespace App\Policies;

use App\Models\DeliveryRoute;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryRoutePolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'saleshead']);
    }

    public function view(User $user, DeliveryRoute $route): bool
    {
        return $user->role === 'admin'
            || $user->role === 'saleshead'
            || $user->id === $route->delivery_boy_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'deliveryboy';
    }

    public function update(User $user, DeliveryRoute $route): bool
    {
        return $user->id === $route->delivery_boy_id && $route->status !== 'blocked';
    }

    public function block(User $user, DeliveryRoute $route): bool
    {
        return $user->role === 'admin';
    }

    public function unblock(User $user, DeliveryRoute $route): bool
    {
        return $user->role === 'admin';
    }
}
