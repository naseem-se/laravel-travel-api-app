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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 8)->default(config('app.currency', 'USD'));
            $table->enum('status', ['pending','approved','rejected','paid'])->default('pending');
            $table->string('payout_provider')->nullable(); // stripe|paypal
            $table->string('payout_provider_id')->nullable();
            $table->text('admin_note')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
