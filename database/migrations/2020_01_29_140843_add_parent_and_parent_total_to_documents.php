<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddParentAndParentTotalToDocuments
 */
class AddParentAndParentTotalToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('id');
            $table->decimal('parent_subtotal', 8, 2)->default(0)->after('total');
    
            $table->foreign('parent_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
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
