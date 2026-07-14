<?php

use Illuminate\Support\Facades\Schedule;

// Auto-block deliveries still in progress after 8 PM
Schedule::command('deliveries:auto-block')
    ->dailyAt('20:00')
    ->withoutOverlapping();
