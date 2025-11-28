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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // traveler and experience (use your existing users & experiences)
            $table->foreignId('traveler_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('experience_id')->constrained('experiences')->onDelete('cascade');

            $table->dateTime('start_at')->nullable();
            $table->integer('guests')->default(1);

            // addons stored array: [{title,price}]
            // $table->json('addons')->nullable();

            // payment summary
            $table->decimal('subtotal', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->string('currency', 8)->default(config('app.currency', 'USD'));

            $table->enum('status', ['pending_payment','paid','cancelled','completed','refunded'])->default('pending_payment');

            // provider summary
            $table->string('payment_provider')->nullable(); // stripe|paypal
            $table->string('payment_provider_id')->nullable(); // paymentIntent / order id / capture id
            $table->string('refund_provider_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
