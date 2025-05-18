<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('offer_id')->constrained('offers')->cascadeOnDelete()->cascadeOnUpdate() ;
            $table->string('image') ;
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('offer_images');
    }
};