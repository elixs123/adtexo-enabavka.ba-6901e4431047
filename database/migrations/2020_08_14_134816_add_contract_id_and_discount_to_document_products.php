<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddContractIdAndDiscountToDocumentProducts
 */
class AddContractIdAndDiscountToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->unsignedInteger('contract_id')->nullable()->after('client_id');
            $table->decimal('contract_discount', 8, 2)->default(0)->after('product_id');
    
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('no action')->onUpdate('no action');
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
            $table->dropForeign(['contract_id']);
        });
        
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropColumn(['contract_id', 'contract_discount']);
        });
    }
}
