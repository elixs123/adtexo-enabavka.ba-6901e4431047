<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddDiscountsToActions
 */
class AddDiscountsToActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->unsignedDecimal('subtotal_discount', 4, 2)->default(0)->after('subtotal');
            $table->unsignedDecimal('total_discount', 4, 2)->default(0)->after('total');
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
            $table->dropColumn(['subtotal_discount', 'total_discount']);
        });
    }
}
