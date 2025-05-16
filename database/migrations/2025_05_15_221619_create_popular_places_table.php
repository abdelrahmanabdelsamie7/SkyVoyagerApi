<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('popular_places', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('image_cover');
            $table->decimal('price_of_ticket', 10, 2);
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('best_season_to_visit')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('popular_places');
    }
};
