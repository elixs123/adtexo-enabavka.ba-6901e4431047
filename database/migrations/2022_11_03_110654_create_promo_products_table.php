<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePromoProductsTable
 */
class CreatePromoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_products', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->string('product_code', 50);
            $table->unsignedDecimal('promo_qty', 8, 2)->default(0);
            
            // $table->unique(['product_id', 'product_code']);
    
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_code')->references('code')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_products');
    }
}
