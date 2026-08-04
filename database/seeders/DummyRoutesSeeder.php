<?php

namespace Database\Seeders;

use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyRoutesSeeder extends Seeder
{
    private const LOCATIONS = [
        'Gulberg', 'Johar Town', 'Model Town', 'DHA', 'Cantt',
        'Iqbal Town', 'Garden Town', 'Township', 'Valencia Town',
        'Lake City', 'Bahria Town', 'Defence', 'Saddar', 'Mall Road',
        'Ferozpur Road', 'Main Boulevard', 'Raiwind Road', 'Multan Road',
        'Wapda Town', 'EME Society',
    ];

    private const STOP_NAMES = [
        'Client Office', 'Warehouse Check', 'Delivery Point A',
        'Collection Point', 'Service Center', 'Retail Shop',
        'Restaurant', 'Hospital', 'School', 'Bank',
    ];

    public function run(): void
    {
        $deliveryBoyIds = [3, 4];

        foreach ($deliveryBoyIds as $boyId) {
            for ($i = 0; $i < 10; $i++) {
                $location = self::LOCATIONS[array_rand(self::LOCATIONS)];
                $km = round(rand(200, 3000) / 100, 1);
                $daysAgo = rand(0, 6);
                $baseDate = Carbon::today()->subDays($daysAgo)->setHour(rand(9, 17))->setMinute(rand(0, 59));

                $route = DeliveryRoute::create([
                    'delivery_boy_id' => $boyId,
                    'from_location' => 'Office',
                    'to_location' => $location,
                    'total_km' => $km,
                    'boc' => 'BOC-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                    'status' => 'completed',
                    'started_at' => (clone $baseDate)->addMinutes(rand(5, 30)),
                    'created_at' => $baseDate,
                    'updated_at' => (clone $baseDate)->addHours(rand(1, 3)),
                ]);

                $numStops = rand(2, 3);
                $stopKm = 0;
                for ($s = 0; $s < $numStops; $s++) {
                    $stopKm += round(rand(100, 800) / 100, 1);
                    RouteStop::create([
                        'delivery_route_id' => $route->id,
                        'stop_name' => self::STOP_NAMES[array_rand(self::STOP_NAMES)] . ' ' . ($s + 1),
                        'stop_km' => $stopKm,
                        'order' => $s + 1,
                        'created_at' => $baseDate,
                        'updated_at' => $baseDate,
                    ]);
                }
            }
        }
    }
}
