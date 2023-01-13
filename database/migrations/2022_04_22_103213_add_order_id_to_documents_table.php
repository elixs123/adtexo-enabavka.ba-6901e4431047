<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddOrderIdToDocumentsTable
 */
class AddOrderIdToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->nullable()->after('is_payed');
    
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });
        
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
