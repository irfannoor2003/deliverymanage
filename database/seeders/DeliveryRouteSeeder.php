<?php

namespace Database\Seeders;

use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeliveryRouteSeeder extends Seeder
{
    public function run(): void
    {
        $deliveryBoy = User::where('role', 'deliveryboy')->first();
        if (!$deliveryBoy) return;

        // Pending route
        $route1 = DeliveryRoute::create([
            'delivery_boy_id' => $deliveryBoy->id,
            'from_location' => 'Office',
            'to_location' => 'Gulberg, Lahore',
            'total_km' => 12.5,
            'boc' => 'Urgent delivery',
            'status' => 'pending',
        ]);
        RouteStop::create([
            'delivery_route_id' => $route1->id,
            'stop_name' => 'DHA Phase 5',
            'stop_km' => 8.2,
            'order' => 1,
        ]);

        // In-progress route
        $route2 = DeliveryRoute::create([
            'delivery_boy_id' => $deliveryBoy->id,
            'from_location' => 'Office',
            'to_location' => 'Johar Town, Lahore',
            'total_km' => 8.0,
            'boc' => 'Handle with care',
            'status' => 'in_progress',
            'started_at' => now()->subHours(2),
        ]);
        RouteStop::create([
            'delivery_route_id' => $route2->id,
            'stop_name' => 'Cavalry Ground',
            'stop_km' => 4.1,
            'order' => 1,
        ]);

        // Completed route
        $route3 = DeliveryRoute::create([
            'delivery_boy_id' => $deliveryBoy->id,
            'from_location' => 'Office',
            'to_location' => 'Model Town, Lahore',
            'total_km' => 15.0,
            'boc' => 'Fragile items',
            'status' => 'completed',
            'started_at' => now()->subDay(),
        ]);
        RouteStop::create([
            'delivery_route_id' => $route3->id,
            'stop_name' => 'Faisal Town',
            'stop_km' => 7.5,
            'order' => 1,
        ]);
        RouteStop::create([
            'delivery_route_id' => $route3->id,
            'stop_name' => 'Garden Town',
            'stop_km' => 12.0,
            'order' => 2,
        ]);

        // Blocked route
        DeliveryRoute::create([
            'delivery_boy_id' => $deliveryBoy->id,
            'from_location' => 'Office',
            'to_location' => 'Cantt, Lahore',
            'total_km' => 20.0,
            'boc' => 'Call before delivery',
            'status' => 'blocked',
            'blocked_at' => now()->subDay(),
        ]);
    }
}
