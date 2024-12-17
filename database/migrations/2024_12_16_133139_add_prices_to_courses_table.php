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
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('original_price');
            $table->dropColumn('discounted_price');
            $table->decimal('price_lyd', 8, 2)->default(0);
            $table->decimal('discounted_price_lyd', 8, 2)->default(0);
            $table->decimal('price_usd', 8, 2)->default(0);
            $table->decimal('discounted_price_usd', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('price_lyd');
            $table->dropColumn('discounted_price_lyd');
            $table->dropColumn('price_usd');
            $table->dropColumn('discounted_price_usd');
            $table->decimal('original_price', 8, 2)->default(0);
            $table->decimal('discounted_price', 8, 2)->default(0);
        });
    }
};
