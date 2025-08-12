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
        Schema::create('regular_banners', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->string('page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regular_banners');
    }
};
