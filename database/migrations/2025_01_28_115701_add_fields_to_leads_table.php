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
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('labels')->nullable();
            $table->string('email_subscriber_status')->nullable();
            $table->string('sms_subscriber_status')->nullable();
            $table->string('last_activity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('email')->unique()->nullable()->change();
            $table->dropColumn('address');
            $table->dropColumn('company');
            $table->dropColumn('position');
            $table->dropColumn('labels');
            $table->dropColumn('email_subscriber_status');
            $table->dropColumn('sms_subscriber_status');
            $table->dropColumn('last_activity');
        });
    }
};
