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
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_label');
            $table->string('setting_key');
            $table->string('setting_group');
            $table->boolean('setting_status');
            $table->text('setting_description')->nullable();
            $table->string('value_type');
            $table->boolean('boolean_value')->nullable();
            $table->string('string_value')->nullable();
            $table->text('text_value')->nullable();
            $table->string('image_value')->nullable();
            $table->integer('integer_value')->nullable();
            $table->decimal('decimal_value')->nullable();
            $table->date('date_value')->nullable();
            $table->string('file_value')->nullable();
            $table->json('json_value')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }
};
