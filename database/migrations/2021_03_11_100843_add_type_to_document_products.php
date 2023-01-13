<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddTypeToDocumentProducts
 */
class AddTypeToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->string('type', 50)->default('regular')->after('total_loyalty_points');
        });
    
        Schema::table('document_products', function (Blueprint $table) {
            $table->unique(['document_id', 'product_id', 'type']);
        });
        
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropUnique(['document_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropUnique(['document_id', 'product_id', 'type']);
        });
    
        Schema::table('document_products', function (Blueprint $table) {
            $table->unique(['document_id', 'product_id']);
        });
        
        Schema::table('document_products', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
