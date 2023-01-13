<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddTypeToActionProductsTable
 */
class AddTypeToActionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_products', function (Blueprint $table) {
            $table->string('type', 50)->default('action')->after('prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_products', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
