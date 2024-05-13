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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url_title');
            $table->boolean('status');
            $table->string('description')->nullable();
            $table->string('meta_title');
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('image_alt_caption')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_caption')->nullable();
            $table->string('banner_alt_caption')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
