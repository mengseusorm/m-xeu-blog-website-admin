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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_image_url')->unique();
            $table->string('youtube_video_url')->unique();
            $table->string('channel_id')->nullable();
            $table->string('video_id')->unique();
            $table->string('channel_name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->integer('categoryId')->nullable();
            $table->timestamp('published_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
