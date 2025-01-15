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
        Schema::create('standard_fares', function (Blueprint $table) {
            $table->id();
            $table->decimal('distance_range_start', 8, 2); //in kms
            $table->decimal('distance_range_end', 8, 2); //in kms
            $table->decimal('fare', 8, 2); //in NRs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_fares');
    }
};
