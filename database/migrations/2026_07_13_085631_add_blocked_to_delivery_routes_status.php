<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE delivery_routes MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'blocked') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE delivery_routes MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending'");
    }
};
