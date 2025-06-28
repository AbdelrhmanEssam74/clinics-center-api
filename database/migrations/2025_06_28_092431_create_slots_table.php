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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['available', 'booked', 'cancelled'])->default('available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
