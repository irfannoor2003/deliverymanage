<?php

namespace App\Console\Commands;

use App\Models\DeliveryRoute;
use App\Services\DeliveryService;
use Illuminate\Console\Command;

class AutoBlockDeliveries extends Command
{
    protected $signature = 'deliveries:auto-block';
    protected $description = 'Auto-block deliveries that are still in_progress after 8:00 PM';

    public function handle(): int
    {
        $this->info('Checking for overdue deliveries...');

        $now = now();
        $cutoff = $now->copy()->setHour(20)->setMinute(0)->setSecond(0);

        if ($now->lessThan($cutoff)) {
            $this->info('Current time is before 8:00 PM. Skipping.');
            return Command::SUCCESS;
        }

        $routes = DeliveryRoute::where('status', 'in_progress')
            ->whereDate('created_at', '<=', $now->toDateString())
            ->get();

        $service = app(DeliveryService::class);
        $count = 0;

        foreach ($routes as $route) {
            $service->block($route);
            $count++;
        }

        $this->info("Blocked {$count} overdue delivery(s).");

        return Command::SUCCESS;
    }
}
