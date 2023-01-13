<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->text('client_data')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->text('location_data')->nullable();
            $table->string('stock_name')->nullable();
            $table->unsignedDecimal('subtotal')->default(0);
            $table->unsignedDecimal('discount')->default(0);
            $table->unsignedDecimal('tax')->default(0);
            $table->unsignedDecimal('total')->default(0);
            $table->string('status', 50)->nullable();
            $table->timestamps();
    
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade')->onUpdate('no action');
        });
    
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedSmallInteger('position')->default(0);
            $table->string('product_code')->nullable();
            $table->string('name')->nullable();
            $table->unsignedDecimal('quantity')->default(1);
            $table->unsignedDecimal('price')->default(0);
            $table->unsignedDecimal('discount_1')->default(0);
            $table->unsignedDecimal('discount_2')->default(0);
            $table->unsignedDecimal('discount_3')->default(0);
            $table->unsignedDecimal('discount_total')->default(0);
            $table->unsignedDecimal('total')->default(0);
    
            $table->timestamps();
    
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
}
