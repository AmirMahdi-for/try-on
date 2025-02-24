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
        Schema::create('try_on', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('user_id');
            $table->integer('model_file_id');
            $table->integer('garment_file_id');
            $table->enum('category', ['tops', 'bottoms', 'one-pieces'])->nullable();
            $table->json('send_images_result')->nullable();
            $table->json('status_result')->nullable();
            $table->json('generate_result')->nullable();
            $table->integer('send_images_status_code');
            $table->integer('status_request_status_code')->nullable();
            $table->integer('generate_response_status_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('try_on');
    }
};
