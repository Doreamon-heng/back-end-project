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
        Schema::create('slide_shows_image', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slide_show_id');
            $table->string('file_name');
            $table->string('image_url');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_disabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slide_shows_image');
    }
};
