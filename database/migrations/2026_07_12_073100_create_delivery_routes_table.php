<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_boy_id')->constrained('users')->onDelete('cascade');
            $table->string('from_location');
            $table->string('to_location');
            $table->decimal('total_km', 8, 2)->default(0);
            $table->string('boc')->nullable(); // Bill of Consignment
            $table->string('image_path')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_routes');
    }
};
