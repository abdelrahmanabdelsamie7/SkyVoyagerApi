<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('image_cover');
            $table->decimal('price_per_night', 10, 2);
            $table->boolean('dinner_option')->default(true);
            $table->boolean('ac_option')->default(true);
            $table->boolean('hot_tub_option')->default(true);
            $table->integer('num_of_beds');
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
