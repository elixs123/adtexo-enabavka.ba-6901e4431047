<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDocumentChangesTable
 */
class CreateDocumentChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('changed_by')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('value', 200)->nullable();
            $table->timestamps();
    
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_changes');
    }
}
