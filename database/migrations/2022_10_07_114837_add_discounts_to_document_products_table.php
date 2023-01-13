<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddDiscountsToDocumentProductsTable
 */
class AddDiscountsToDocumentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->decimal('discount1', 5, 2)->default(0)->after('subtotal_discounted');
            $table->decimal('discount2', 5, 2)->default(0)->after('discount1');
            $table->decimal('discount3', 5, 2)->default(0)->after('discount2');
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
            $table->dropColumn(['discount1', 'discount2', 'discount3']);
        });
    }
}
