<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_routes', function (Blueprint $table) {
            $table->timestamp('blocked_at')->nullable()->after('status');
            $table->timestamp('unblocked_at')->nullable()->after('blocked_at');
            $table->timestamp('started_at')->nullable()->after('unblocked_at');
            $table->decimal('start_lat', 10, 7)->nullable()->after('started_at');
            $table->decimal('start_lng', 10, 7)->nullable()->after('start_lat');
            $table->decimal('end_lat', 10, 7)->nullable()->after('start_lng');
            $table->decimal('end_lng', 10, 7)->nullable()->after('end_lat');
        });

        Schema::table('route_stops', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('image_path');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_routes', function (Blueprint $table) {
            $table->dropColumn(['blocked_at', 'unblocked_at', 'started_at', 'start_lat', 'start_lng', 'end_lat', 'end_lng']);
        });

        Schema::table('route_stops', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });
    }
};
