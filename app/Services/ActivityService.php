<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityService
{
    public function log(string $action, ?string $description = null, ?int $userId = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getAll(array $filters = [], int $perPage = 20)
    {
        return ActivityLog::with('user')
            ->when($filters['user_id'] ?? null, fn($q, $v) => $q->where('user_id', $v))
            ->when($filters['action'] ?? null, fn($q, $v) => $q->where('action', $v))
            ->when($filters['from_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
