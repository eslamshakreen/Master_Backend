<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('episode_order')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
