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
        Schema::create('stop_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stop_id');
            $table->foreign('stop_id')->references('id')->on('stops')->onDelete('cascade');
            $table->unsignedBigInteger('next_stop_id');
            $table->foreign('next_stop_id')->references('id')->on('stops')->onDelete('cascade');
            $table->decimal('distance',10,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stop_connections');
    }
};
