<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRoutesTable
 */
class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('client_id');
            $table->unsignedTinyInteger('week');
            $table->string('day', 3);
            $table->unsignedSmallInteger('rank')->default(9999);
            $table->timestamps();
    
            $table->index(['person_id']);
            $table->index(['person_id', 'client_id', 'week', 'day']);
            
            $table->unique(['client_id', 'week', 'day']);
    
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
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
        Schema::dropIfExists('routes');
    }
}
