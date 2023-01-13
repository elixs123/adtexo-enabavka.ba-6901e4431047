<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCityTableTable
 */
class CreateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_id', 100);
            $table->string('postal_code', 10);
            $table->string('name', 100);
            $table->string('status', 20)->default('active');
            $table->timestamps();
			
            $table->foreign('country_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
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
        Schema::dropIfExists('cities');
    }
}