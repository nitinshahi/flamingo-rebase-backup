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
        Schema::table('blogs', function (Blueprint $table) {
            //
            $table->string('url_title')->unique()->change();
            $table->string('abstract')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->string('meta_keywords')->nullable()->change();
            $table->string('meta_description')->nullable()->change();
            $table->string('avatar_image')->nullable()->change();
            $table->string('avatar_text')->nullable()->change();
            $table->string('avatar_caption')->nullable()->change();
            $table->string('banner_image')->nullable()->change();
            $table->string('banner_text')->nullable()->change();
            $table->string('banner_caption')->nullable()->change();
            $table->after('banner_caption', function (Blueprint $table){
                $table->softDeletes();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
