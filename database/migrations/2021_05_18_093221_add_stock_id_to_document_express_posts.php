<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddStockIdToDocumentExpressPosts
 */
class AddStockIdToDocumentExpressPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->unsignedInteger('stock_id')->nullable()->default(2)->after('id');
    
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dropForeign(['stock_id']);
        });
        
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dropColumn(['stock_id']);
        });
    }
}
