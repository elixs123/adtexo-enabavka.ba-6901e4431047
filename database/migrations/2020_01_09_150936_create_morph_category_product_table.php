<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMorphCategoryProductTable
 */
class CreateMorphCategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_category', function (Blueprint $table) {
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('category_id');
            
            $table->unique(['client_id', 'category_id']);
    
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action')->onUpdate('no action');
        });
        
        Schema::create('client_product', function (Blueprint $table) {
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('product_id');
            
            $table->unique(['client_id', 'product_id']);
    
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('person_category', function (Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('category_id');
        
            $table->unique(['person_id', 'category_id']);
        
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('person_product', function (Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('product_id');
        
            $table->unique(['person_id', 'product_id']);
        
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_category');
        Schema::dropIfExists('client_product');
        Schema::dropIfExists('person_category');
        Schema::dropIfExists('person_product');
    }
}
