<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddLoyaltyPointsToDocumentProducts
 */
class AddLoyaltyPointsToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->tinyInteger('loyalty_points')->default(0)->after('vpc_discounted');
            $table->integer('total_loyalty_points')->default(0)->after('subtotal_discounted');
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
            $table->dropColumn(['loyalty_points', 'total_loyalty_points']);
        });
    }
}
