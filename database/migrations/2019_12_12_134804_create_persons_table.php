<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique()->nullable();
            $table->string('name', 100);
            $table->string('type_id', 50);
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('note', 255)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
            
            $table->index(['type_id']);
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('type_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
