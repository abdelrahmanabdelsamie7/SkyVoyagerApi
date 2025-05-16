<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('image_cover');
            $table->integer('num_of_tickets');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('city');
            $table->decimal('price_per_ticket', 10, 2);
            $table->text('description')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
