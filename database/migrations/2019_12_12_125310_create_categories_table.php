<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
            $table->increments('id');
			$table->string('father_id', 100);
			$table->string('list_of_parents', 100);
			$table->integer('priority')->unsigned()->default(1);
            $table->string('status', 20)->default('active');
			$table->integer('lft')->unsigned();
			$table->integer('rgt')->unsigned();
			$table->timestamps();
			
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');			
			
		});
		
        Schema::create('category_translations', function (Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('category_id');			
            $table->char('lang_id', 2);
			$table->string('name', 100);
			$table->string('description')->nullable();
			$table->string('slug', 255)->nullable();
			$table->string('path')->nullable();
            $table->timestamps();
			
			$table->unique(['category_id', 'lang_id']);		
            $table->unique(['slug', 'lang_id']);							
        });			
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
		Schema::drop('category_translations');		
	}
}