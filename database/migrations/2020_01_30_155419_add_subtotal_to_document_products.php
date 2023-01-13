<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddSubtotalToDocumentProducts
 */
class AddSubtotalToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->decimal('subtotal', 8, 2)->default(0)->after('total_discounted');
            $table->decimal('subtotal_discounted', 8, 2)->default(0)->after('subtotal');
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
