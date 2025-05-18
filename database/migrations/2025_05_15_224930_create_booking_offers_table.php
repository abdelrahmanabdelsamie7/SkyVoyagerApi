<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('schedule_id')->constrained('flight_schedules')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('num_of_tickets');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('booking_offers');
    }
};
