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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('languages')->nullable();
            $table->string('currency')->nullable();

            $table->string('id_upload')->nullable();
            $table->enum('id_upload_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('business_certificate')->nullable();
            $table->enum('business_certificate_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('license')->nullable();
            $table->enum('license_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('description')->nullable();
            $table->text('cultural_experience')->nullable();

            // multiple photos saved in JSON
            $table->json('upload_photos')->nullable();
            $table->enum('overall_status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
