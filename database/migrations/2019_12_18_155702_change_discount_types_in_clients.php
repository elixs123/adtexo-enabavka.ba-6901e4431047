<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ChangeDiscountTypesInClients
 */
class ChangeDiscountTypesInClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedDecimal('payment_discount', 4, 1)->default(0)->after('payment_type');
            $table->unsignedDecimal('discount_value1', 4, 1)->default(0)->after('payment_discount');
            $table->unsignedDecimal('discount_value2', 4, 1)->default(0)->after('discount_value1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
