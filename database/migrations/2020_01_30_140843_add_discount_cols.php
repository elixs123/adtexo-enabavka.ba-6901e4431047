<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddDiscountCols
 */
class AddDiscountCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->decimal('mpc_discounted', 8, 2)->default(0)->after('mpc');
            $table->decimal('vpc_discounted', 8, 2)->default(0)->after('vpc');
            $table->decimal('total_discounted', 8, 2)->default(0)->after('total');
        });
		
        Schema::table('documents', function (Blueprint $table) {
            $table->decimal('subtotal_discounted', 8, 2)->default(0)->after('subtotal');
            $table->decimal('total_discounted', 8, 2)->default(0)->after('total');
            $table->decimal('payment_discount', 4, 2)->default(0)->after('total_discounted');
            $table->decimal('discount_value1', 8, 2)->default(0)->after('payment_discount');
			
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
