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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url_title');
            $table->string('abstract');
            $table->boolean('status');
            $table->boolean('is_featured');
            $table->date('date');
            $table->string('description');
            $table->string('meta_keywords');
            $table->string('meta_description');
            $table->string('avatar_image');
            $table->string('avatar_text');
            $table->string('avatar_caption');
            $table->string('banner_image');
            $table->string('banner_text');
            $table->string('banner_caption');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
