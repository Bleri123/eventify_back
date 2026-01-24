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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('first_name', 150);
            $table->string('email', 150);
            $table->integer('seats_reserved')->default(0);
            $table->string('row_reserved')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'reserved', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamps();

            $table->index(['screening_id', 'booking_id']);
            $table->index(['user_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
