<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClientActionsTable
 */
class CreateClientActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_actions', function (Blueprint $table) {
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('action_id');
    
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_actions');
    }
}
