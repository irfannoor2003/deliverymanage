<?php

namespace App\Services;

use App\Models\User;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll()
    {
        return User::orderBy('role')->orderBy('name')->get();
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'pin' => Hash::make($data['pin']),
            'role' => $data['role'],
        ]);

        event(new UserCreated($user));

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'role' => $data['role'],
        ];

        if (!empty($data['pin'])) {
            $updateData['pin'] = Hash::make($data['pin']);
        }

        $user->update($updateData);

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
        event(new UserDeleted($user));
    }

    public function getStats(): array
    {
        return [
            'total_users' => User::count(),
            'delivery_boys' => User::where('role', 'deliveryboy')->count(),
            'total_routes' => \App\Models\DeliveryRoute::count(),
            'pending_routes' => \App\Models\DeliveryRoute::where('status', 'pending')->count(),
            'in_progress_routes' => \App\Models\DeliveryRoute::where('status', 'in_progress')->count(),
            'completed_routes' => \App\Models\DeliveryRoute::where('status', 'completed')->count(),
            'blocked_routes' => \App\Models\DeliveryRoute::where('status', 'blocked')->count(),
            'messages' => \App\Models\Message::count(),
        ];
    }
}
