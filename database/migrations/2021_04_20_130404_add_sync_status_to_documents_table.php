<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncStatusToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
				$table->string('sync_status', 50)
				->after('internal_status')
				->nullable()
				->default(null);
				
				$table->dateTime('date_of_sync')
				->after('date_of_payment')
				->nullable()
				->default(null);				
				
				
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
             $table->dropColumn('sync_status');
             $table->dropColumn('date_of_sync');			 
        });
    }
}