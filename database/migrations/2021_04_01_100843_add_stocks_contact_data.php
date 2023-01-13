<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddStocksContactData
 */
class AddStocksContactData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
			$table->string('email', 200)->after('country_id');
			$table->string('phone', 20)->after('country_id');
			$table->string('original_name', 200)->after('name');			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'original_name']);
        });
    }
}