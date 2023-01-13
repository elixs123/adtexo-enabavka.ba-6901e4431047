<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProductProductsTable
 */
class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->string('country_id', 20);
            $table->decimal('mpc', 8, 2);
            $table->decimal('vpc', 8, 2);
            $table->decimal('mpc_eur', 8, 2);
            $table->decimal('vpc_eur', 8, 2);            
            $table->timestamps();
            
            $table->foreign('country_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');			
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			
            $table->unique(['product_id', 'country_id']);			
			
        });	
		
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('stock_id');
            $table->decimal('qty', 8, 2);
            $table->string('action', 20);
            $table->text('note')->nullable()->default(null);			
            $table->timestamps();
            
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('no action')->onUpdate('no action');			
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			
            $table->index(['product_id', 'stock_id']);			
			
        });		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
		Schema::dropIfExists('product_translations');
    }
}
