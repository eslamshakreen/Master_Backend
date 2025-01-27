<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_price_visible')->default(true);
            $table->boolean('is_discount_visible')->default(false);
            $table->string('headline_one')->nullable();
            $table->string('headline_two')->nullable();
            $table->string('headline_three')->nullable();
            $table->string('description_one')->nullable();
            $table->string('description_two')->nullable();
            $table->string('description_three')->nullable();
            $table->string('image_one')->nullable();
            $table->string('image_two')->nullable();
            $table->string('image_three')->nullable();
            $table->string('call_to_action_one')->nullable();
            $table->string('call_to_action_two')->nullable();
            $table->string('call_to_action_three')->nullable();
            $table->string('call_to_action_link_one')->nullable();
            $table->string('call_to_action_link_two')->nullable();
            $table->string('call_to_action_link_three')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_price_visible');
            $table->dropColumn('is_discount_visible');
            $table->dropColumn('headline_one');
            $table->dropColumn('headline_two');
            $table->dropColumn('headline_three');
            $table->dropColumn('description_one');
            $table->dropColumn('description_two');
            $table->dropColumn('description_three');
            $table->dropColumn('image_one');
            $table->dropColumn('image_two');
            $table->dropColumn('image_three');
            $table->dropColumn('call_to_action_one');
            $table->dropColumn('call_to_action_two');
            $table->dropColumn('call_to_action_three');
            $table->dropColumn('call_to_action_link_one');
            $table->dropColumn('call_to_action_link_two');
            $table->dropColumn('call_to_action_link_three');
        });
    }
};
