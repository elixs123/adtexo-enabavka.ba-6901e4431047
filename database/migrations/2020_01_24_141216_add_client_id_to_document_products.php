<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddClientIdToDocumentProducts
 */
class AddClientIdToDocumentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_products', function (Blueprint $table) {
			$table->unsignedInteger('client_id')->after('document_id')->default(1);
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');												
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
