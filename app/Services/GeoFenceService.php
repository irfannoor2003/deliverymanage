<?php

namespace App\Services;

class GeoFenceService
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

    public function isWithinRange(float $lat, float $lng): bool
    {
        return $this->distance($lat, $lng) <= $this->allowedRadiusKm;
    }

    public function distance(float $lat, float $lng): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat - $this->officeLat);
        $dLng = deg2rad($lng - $this->officeLng);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($this->officeLat)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function validate(float $lat, float $lng): ?string
    {
        if (!$this->isWithinRange($lat, $lng)) {
            return __('messages.outside_office_radius', ['radius' => $this->allowedRadiusKm, 'distance' => number_format($this->distance($lat, $lng), 2)]);
        }
        return null;
    }
}
