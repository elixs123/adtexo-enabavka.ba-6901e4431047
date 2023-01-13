<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressPostEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_post_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->string('type', 100);
            $table->longText('response')->nullable();
            $table->timestamps();
    
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('express_post_events');
    }
}
