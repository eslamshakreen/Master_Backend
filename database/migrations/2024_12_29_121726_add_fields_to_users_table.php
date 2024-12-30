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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_2')->nullable();
            $table->string('country')->nullable();
            $table->string('degree')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_2');
            $table->dropColumn('country');
            $table->dropColumn('degree');
            $table->dropColumn('company');
            $table->dropColumn('job_title');
        });
    }
};
