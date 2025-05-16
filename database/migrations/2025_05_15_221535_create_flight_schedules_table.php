<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('offer_id')->constrained('offers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('departure_city');
            $table->time('departure_time');
            $table->string('arrival_city')->nullable();
            $table->time('arrival_time')->nullable();
            $table->decimal('price_multiplier', 5, 2)->default(1.00);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('flight_schedules');
    }
};
