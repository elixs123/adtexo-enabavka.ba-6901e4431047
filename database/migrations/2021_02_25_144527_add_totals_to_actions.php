<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddTotalsToActions
 */
class AddTotalsToActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->decimal('subtotal', 9, 3)->default(0)->after('product_prices');
            $table->decimal('subtotal_discounted', 9, 3)->default(0)->after('subtotal');
            $table->decimal('total', 9, 3)->default(0)->after('subtotal_discounted');
            $table->decimal('total_discounted', 9, 3)->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'subtotal_discounted', 'total', 'total_discounted']);
        });
    }
}
