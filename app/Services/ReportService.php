<?php

namespace App\Services;

use App\Models\DeliveryRoute;
use App\Models\User;

class ReportService
{
    public function getFilteredRoutes(array $filters)
    {
        return DeliveryRoute::with('deliveryBoy')
            ->when($filters['delivery_boy_id'] ?? null, fn($q, $v) => $q->where('delivery_boy_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['from_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSummary($routes): array
    {
        return [
            'total' => $routes->count(),
            'completed' => $routes->where('status', 'completed')->count(),
            'in_progress' => $routes->where('status', 'in_progress')->count(),
            'pending' => $routes->where('status', 'pending')->count(),
            'blocked' => $routes->where('status', 'blocked')->count(),
            'total_km' => $routes->sum('total_km'),
        ];
    }

    public function getDeliveryBoys()
    {
        return User::where('role', 'deliveryboy')->get();
    }
}
