<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('fiscal_receipt_no', 50)->nullable();
            $table->datetime('fiscal_receipt_datetime')->nullable();
            $table->decimal('fiscal_receipt_amount', 18, 2)->nullable();
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
            $table->dropColumn('fiscal_receipt_no');
            $table->dropColumn('fiscal_receipt_datetime');
            $table->dropColumn('fiscal_receipt_amount');

        });
    }
}
