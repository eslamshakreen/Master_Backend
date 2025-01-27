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
        Schema::table('heroes', function (Blueprint $table) {
            $table->boolean('is_call_to_action_visible')->default(false);
            $table->string('call_to_action_link')->nullable();
            $table->string('call_to_action_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn('is_call_to_action_visible');
            $table->dropColumn('call_to_action_link');
            $table->dropColumn('call_to_action_title');
        });
    }
};
