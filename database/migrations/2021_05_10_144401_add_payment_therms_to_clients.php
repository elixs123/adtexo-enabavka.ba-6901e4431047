<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddPaymentThermsToClients
 */
class AddPaymentThermsToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('payment_therms', 50)->default('payment')->after('note');
    
            $table->foreign('payment_therms')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['payment_therms']);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('payment_therms');
        });
    }
}
