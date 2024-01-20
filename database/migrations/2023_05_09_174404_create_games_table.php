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
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->foreignId('developer_id')->nullable()->references('id')->on('developers');
            $table->foreignId('requirement_id')->nullable()->references('id')->on('requirements');

            $table->string('name');
            $table->string('slug');
            $table->string('release');
            $table->string('rating');
            $table->string('age_rating')->nullable();
            $table->string('playtime');
            $table->string('meta_rating')->nullable();
            $table->text("description");
            $table->string('background_image');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
