<?php

namespace App\Services;

use App\Models\User;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $pin): ?User
    {
        $users = User::all();
        foreach ($users as $user) {
            if (Hash::check($pin, $user->pin)) {
                Auth::login($user);
                request()->session()->regenerate();
                event(new UserLoggedIn($user));
                return $user;
            }
        }
        return null;
    }

    public function logout(): void
    {
        $user = Auth::user();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        if ($user) {
            event(new UserLoggedOut($user));
        }
    }

    public function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'saleshead' => redirect()->route('saleshead.dashboard'),
            'deliveryboy' => redirect()->route('deliveryboy.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
