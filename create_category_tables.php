<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('categories')) {
    Schema::create('categories', function ($table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

if (!Schema::hasTable('categories_images')) {
    Schema::create('categories_images', function ($table) {
        $table->id();
        $table->unsignedBigInteger('category_id');
        $table->string('image_url');
        $table->string('file_name');
        $table->timestamps();
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
}

echo "created\n";
