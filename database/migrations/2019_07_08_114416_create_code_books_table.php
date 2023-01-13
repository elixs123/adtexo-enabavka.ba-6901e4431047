<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCodeBooksTable
 */
class CreateCodeBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('code', 50);
            $table->string('type', 50);
            $table->timestamps();
            
            $table->index('type');
            
            $table->unique(['code', 'type']);
        });
        
        Schema::table('users', function (Blueprint $table) {
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
        Schema::dropIfExists('code_books');
    }
}
