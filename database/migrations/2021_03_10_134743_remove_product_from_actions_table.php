<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductFromActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'product_prices']);
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
            $table->unsignedInteger('product_id')->nullable()->after('stock_id');
            $table->text('product_prices')->nullable()->after('product_id');
    
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
        });
    }
}
