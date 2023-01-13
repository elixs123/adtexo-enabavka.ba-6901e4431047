<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddNewColsToProducts
 */
class AddNewColsToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit_id', 50)->nullable();
            $table->string('packing', 50)->nullable();
            $table->string('transport_packaging', 50)->nullable();
            $table->string('palette', 50)->nullable();	
			
            $table->foreign('unit_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');									 
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
