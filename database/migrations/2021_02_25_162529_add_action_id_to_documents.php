<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddActionIdToDocuments
 */
class AddActionIdToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('action_id')->nullable()->after('stock_id');
    
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('no action')->onUpdate('no action');
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
            $table->dropForeign(['action_id']);
        });
        
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['action_id']);
        });
    }
}
