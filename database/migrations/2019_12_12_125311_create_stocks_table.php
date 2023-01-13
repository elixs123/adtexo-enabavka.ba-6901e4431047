<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stocks', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name', 191);
            $table->string('address', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country_id', 50)->nullable();
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
		Schema::drop('stocks');
	}
}