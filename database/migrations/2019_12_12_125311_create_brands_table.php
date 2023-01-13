<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands', function(Blueprint $table)
		{
            $table->increments('id');
			$table->string('name', 100)->unique();
			$table->string('logo', 100);
			$table->string('slug', 100)->unique();
			$table->integer('priority')->unsigned()->default(1);
            $table->string('status', 20)->default('active');
			$table->timestamps();
			
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
		Schema::drop('brands');
	}
}