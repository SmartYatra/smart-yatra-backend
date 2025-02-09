<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade'); // Links to the buses table
            $table->foreignId('route_id')->constrained()->onDelete('cascade'); // Links to the routes table
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->integer('current_passenger_count')->default(0);
            $table->integer('total_passenger_count')->default(0);
            $table->decimal('total_fare_collected', 10, 2)->default(0.00);
            $table->decimal('distance_traveled', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
