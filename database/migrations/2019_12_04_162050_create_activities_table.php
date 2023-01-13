<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateActivitiesTable
 */
class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_type', 191);
            $table->unsignedInteger('item_id');
            $table->text('item_object');
            $table->string('name', 191);
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
    
            $table->index(array('item_type', 'item_id'));
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
