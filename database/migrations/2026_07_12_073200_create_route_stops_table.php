<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_route_id')->constrained('delivery_routes')->onDelete('cascade');
            $table->string('stop_name');
            $table->decimal('stop_km', 8, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_stops');
    }
};
