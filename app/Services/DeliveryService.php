<?php

namespace App\Services;

use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use App\Events\DeliveryStarted;
use App\Events\DeliveryCompleted;
use App\Events\DeliveryBlocked;
use App\Events\DeliveryUnblocked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryService
{
    public const OFFICE_LAT = 31.540046340012797;
    public const OFFICE_LNG = 74.3315299530999;
    public const ALLOWED_RADIUS_KM = 4;

    public function getDeliveryBoyRoutes()
    {
        return DeliveryRoute::where('delivery_boy_id', Auth::id())
            ->with('stops')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDeliveryBoyStats()
    {
        $routes = $this->getDeliveryBoyRoutes();
        return [
            'total_routes' => $routes->count(),
            'pending_routes' => $routes->where('status', 'pending')->count(),
            'in_progress_routes' => $routes->where('status', 'in_progress')->count(),
            'completed_routes' => $routes->where('status', 'completed')->count(),
            'blocked_routes' => $routes->where('status', 'blocked')->count(),
        ];
    }

    public function getAllRoutes()
    {
        return DeliveryRoute::with('deliveryBoy', 'stops')
            ->orderBy('created_at', 'desc')
            ->get();
    }

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

    public function getRecentRoutes(int $limit = 10)
    {
        return DeliveryRoute::with('deliveryBoy')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function createRoute(array $data, $image = null, $audio = null): DeliveryRoute
    {
        $audioPath = null;
        if ($audio) {
            $audioPath = $audio->store('route-audio', 'public');
        }

        $route = DeliveryRoute::create([
            'delivery_boy_id' => Auth::id(),
            'from_location' => $data['from_location'],
            'to_location' => $data['to_location'],
            'total_km' => $data['total_km'] ?? 0,
            'boc' => $data['boc'] ?? null,
            'image_path' => null,
            'audio_path' => $audioPath,
            'status' => 'pending',
        ]);

        return $route;
    }

    public function updateStatus(DeliveryRoute $route, string $status): void
    {
        if (!in_array($status, ['pending', 'in_progress', 'completed', 'blocked'])) {
            return;
        }

        if ($route->status === $status) {
            return;
        }

        $route->update(['status' => $status]);

        match ($status) {
            'in_progress' => event(new DeliveryStarted($route)),
            'completed' => event(new DeliveryCompleted($route)),
            'blocked' => event(new DeliveryBlocked($route)),
            default => null,
        };
    }

    public function addPitStop(DeliveryRoute $route, array $data, $image = null, $audio = null): RouteStop
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('stop-images', 'public');
        }

        $audioPath = null;
        if ($audio) {
            $audioPath = $audio->store('stop-audio', 'public');
        }

        $order = $route->stops()->max('order') + 1;

        return RouteStop::create([
            'delivery_route_id' => $route->id,
            'stop_name' => $data['stop_name'],
            'stop_km' => $data['stop_km'] ?? 0,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
            'order' => $order,
        ]);
    }

    public function block(DeliveryRoute $route): void
    {
        $route->update(['status' => 'blocked', 'blocked_at' => now()]);
        event(new DeliveryBlocked($route));
    }

    public function unblock(DeliveryRoute $route): void
    {
        $route->update(['status' => 'pending', 'unblocked_at' => now()]);
        event(new DeliveryUnblocked($route));
    }

    public function isOutsideGeoFence(?float $lat, ?float $lng): bool
    {
        if ($lat === null || $lng === null) {
            return true;
        }

        $distance = $this->haversineDistance(
            self::OFFICE_LAT, self::OFFICE_LNG,
            $lat, $lng
        );

        return $distance > self::ALLOWED_RADIUS_KM;
    }

    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function getBlockedRoutes()
    {
        return DeliveryRoute::where('status', 'blocked')->with('deliveryBoy', 'stops')->get();
    }
}
