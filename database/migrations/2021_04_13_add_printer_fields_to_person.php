<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrinterFieldsToPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('printer_type', 100)->nullable();
            $table->string('printer_receipt_url', 500)->nullable();
            $table->string('printer_access_token', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('printer_type');
            $table->dropColumn('printer_receipt_url');
            $table->dropColumn('printer_access_token');
        });
    }
}
