<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddRelatedToProducts
 */
class AddCurrencyOnStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->string('currency_id', 10)->nullable()->after('code');
            $table->foreign('currency_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
			
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
