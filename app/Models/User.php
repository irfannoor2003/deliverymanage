<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'pin',
        'role',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'pin' => 'hashed',
        ];
    }

    public function deliveryRoutes()
    {
        return $this->hasMany(DeliveryRoute::class, 'delivery_boy_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSalesHead(): bool
    {
        return $this->role === 'saleshead';
    }

    public function isDeliveryBoy(): bool
    {
        return $this->role === 'deliveryboy';
    }

    public function hasBlockedRoutes(): bool
    {
        return $this->deliveryRoutes()->where('status', 'blocked')->exists();
    }

    public function hasActiveRoute(): bool
    {
        return $this->deliveryRoutes()->where('status', 'in_progress')->exists();
    }
}
