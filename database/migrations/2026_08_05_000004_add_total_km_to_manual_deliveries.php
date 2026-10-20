<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manual_deliveries', function (Blueprint $table) {
            $table->decimal('total_km', 8, 2)->default(0)->after('delivery_time');
        });
    }

    public function down(): void
    {
        Schema::table('manual_deliveries', function (Blueprint $table) {
            $table->dropColumn('total_km');
        });
    }
};