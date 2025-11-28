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
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('payer_id')->constrained('users');
            $table->string('provider'); // stripe|paypal
            $table->string('provider_payment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 8)->default(config('app.currency', 'USD'));
            $table->enum('status', ['pending','succeeded','refunded','failed'])->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();
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
