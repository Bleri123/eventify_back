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
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('showroom_id');
            $table->dateTime('start_time');
            $table->decimal('base_price', 10, 2);
            $table->enum('status', ['scheduled', 'on_sale', 'completed', 'cancelled'])->default('on_sale');
            $table->timestamps();

            $table->index(['movie_id', 'start_time']);
            $table->index(['showroom_id', 'start_time']);

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('showroom_id')->references('id')->on('showrooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
