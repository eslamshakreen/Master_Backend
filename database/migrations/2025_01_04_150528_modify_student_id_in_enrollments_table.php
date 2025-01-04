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
        Schema::table('enrollments', function (Blueprint $table) {
            // 1. أزل المفتاح الأجنبي القديم
            $table->dropForeign(['student_id']);

            $table->foreign('student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {

            $table->dropForeign(['student_id']);

            // 2. ثم أعد إنشاء المفتاح الأجنبي القديم
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }
};
