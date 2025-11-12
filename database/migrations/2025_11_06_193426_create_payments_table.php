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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id');
            $table->decimal('total_price', 10,2);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('transaction_ref', 100)->unique();
            $table->timestamps();

            $table->index(['booking_id', 'status']);

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
