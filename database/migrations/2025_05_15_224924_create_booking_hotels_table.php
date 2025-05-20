<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_hotels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('hotel_id')->constrained('hotels')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('num_of_beds')->default(1);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('booking_hotels');
    }
};
