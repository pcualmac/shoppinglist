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
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->decimal('spending_limit_usd', 10, 2)->nullable()->after('spending_limit');
            $table->decimal('spending_limit_eur', 10, 2)->nullable()->after('spending_limit_usd');
            $table->decimal('spending_limit_gbp', 10, 2)->nullable()->after('spending_limit_eur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->dropColumn([
                'spending_limit_usd',
                'spending_limit_eur',
                'spending_limit_gbp',
            ]);
        });
    }
};
