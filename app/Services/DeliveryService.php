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
    public float $officeLat;
    public float $officeLng;
    public float $allowedRadiusKm;

    public function __construct()
    {
        $this->officeLat = (float) env('OFFICE_LAT', 31.540046340012797);
        $this->officeLng = (float) env('OFFICE_LNG', 74.3315299530999);
        $this->allowedRadiusKm = ((float) env('OFFICE_RADIUS', 4000)) / 1000;
    }

    public function getDeliveryBoyRoutes()
    {
        return DeliveryRoute::where('delivery_boy_id', Auth::id())
            ->with('stops')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getDeliveryBoyStats()
    {
        $base = DeliveryRoute::where('delivery_boy_id', Auth::id());
        return [
            'total_routes' => (clone $base)->count(),
            'pending_routes' => (clone $base)->where('status', 'pending')->count(),
            'in_progress_routes' => (clone $base)->where('status', 'in_progress')->count(),
            'completed_routes' => (clone $base)->where('status', 'completed')->count(),
            'blocked_routes' => (clone $base)->where('status', 'blocked')->count(),
        ];
    }

    public function getAllRoutes()
    {
        return DeliveryRoute::with('deliveryBoy', 'stops')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getFilteredRoutes(array $filters, ?int $perPage = null)
    {
        $q = DeliveryRoute::with('deliveryBoy')
            ->when($filters['delivery_boy_id'] ?? null, fn($q, $v) => $q->where('delivery_boy_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['from_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at', 'desc');

        return $perPage ? $q->paginate($perPage) : $q->get();
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
        $images = [];
        if ($image) {
            if (is_iterable($image)) {
                foreach ($image as $img) {
                    if ($img) {
                        $images[] = $img->store('stop-images', 'public');
                    }
                }
            } else {
                $images[] = $image->store('stop-images', 'public');
            }
        }

        $audioPath = null;
        if ($audio) {
            $audioPath = $audio->store('stop-audio', 'public');
        }

        $order = $route->stops()->max('order') + 1;

        return RouteStop::create([
            'delivery_route_id' => $route->id,
            'stop_name' => $data['stop_name'],
            'stop_km' => $data['stop_km'],
            'image_path' => $images[0] ?? null,
            'images' => $images ?: null,
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
            $this->officeLat, $this->officeLng,
            $lat, $lng
        );

        return $distance > $this->allowedRadiusKm;
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
