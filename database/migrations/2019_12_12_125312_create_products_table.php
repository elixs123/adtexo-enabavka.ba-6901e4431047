<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProductProductsTable
 */
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('video', 255)->nullable();
            $table->string('barcode', 50)->nullable();
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('length')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedtinyInteger('loyalty_points')->default(0);
            $table->unsignedtinyInteger('is_gratis')->default(0);
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('rang')->default(1);
            $table->timestamps();

            $table->unique(['code']);
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('no action')->onUpdate('no action');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action')->onUpdate('no action');
            //$table->foreign('supplier_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->char('lang_id', 2);
            $table->string('name', 255);
            $table->text('text')->nullable();
            $table->text('search')->nullable();
            $table->string('link', 255)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'lang_id']);
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
