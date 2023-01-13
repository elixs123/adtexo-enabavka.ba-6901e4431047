<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddDiscountValuesToDocumentProducts
 */
class AddDiscountValuesToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->unsignedDecimal('mpc_discount', 4, 2)->default(0)->after('mpc');
            $table->unsignedDecimal('vpc_discount', 4, 2)->default(0)->after('vpc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropColumn(['mpc_discount', 'vpc_discount']);
        });
    }
}
