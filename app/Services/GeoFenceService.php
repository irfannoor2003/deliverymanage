<?php

namespace App\Services;

class GeoFenceService
{
    public const OFFICE_LAT = 31.540046340012797;
    public const OFFICE_LNG = 74.3315299530999;
    public const ALLOWED_RADIUS_KM = 4;

    public function isWithinRange(float $lat, float $lng): bool
    {
        return $this->distance($lat, $lng) <= self::ALLOWED_RADIUS_KM;
    }

    public function distance(float $lat, float $lng): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat - self::OFFICE_LAT);
        $dLng = deg2rad($lng - self::OFFICE_LNG);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad(self::OFFICE_LAT)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function validate(float $lat, float $lng): ?string
    {
        if (!$this->isWithinRange($lat, $lng)) {
            return __('messages.outside_office_radius', ['radius' => self::ALLOWED_RADIUS_KM, 'distance' => number_format($this->distance($lat, $lng), 2)]);
        }
        return null;
    }
}
