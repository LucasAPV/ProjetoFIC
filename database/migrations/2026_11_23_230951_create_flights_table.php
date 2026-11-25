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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_search_id')->constrained()->cascadeOnDelete();
            $table->string('departure_airport_name')->nullable();
            $table->string('arrival_airport_name')->nullable();
            $table->dateTime('departure_at')->nullable();
            $table->dateTime('arrival_at')->nullable();
            $table->unsignedInteger('duration_minutes');
            $table->unsignedTinyInteger('stops');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
