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
        Schema::table('payments', function (Blueprint $table) {


            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
            $table->dropForeign('payments_student_id_foreign');
            $table->dropColumn('student_id');
            $table->dropColumn('amount_paid');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('paid_amount');
            $table->dropForeign('payments_enrollment_id_foreign');
            $table->dropColumn('enrollment_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
