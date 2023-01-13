<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddLuceedIdToProducts
 */
class AddLuceedIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('luceed_uid', 50)->nullable()->after('code');
        });
        
        Schema::table('document_products', function (Blueprint $table) {
            $table->string('luceed_uid', 50)->nullable()->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('luceed_uid');
        });
        
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropColumn('luceed_uid');
        });
    }
}
