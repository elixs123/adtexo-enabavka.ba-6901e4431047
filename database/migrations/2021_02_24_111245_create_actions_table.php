<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActionsTable
 */
class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type_id', 50);
            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->string('stock_type', 50);
            $table->unsignedInteger('qty')->default(0);
            $table->unsignedInteger('bought')->default(0);
            $table->unsignedInteger('reserved')->default(0);
            $table->unsignedInteger('stock_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->text('product_prices')->nullable();
            $table->string('status', 50);
            $table->timestamps();
    
            $table->foreign('type_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('action_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('action_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('qty')->default(0);
            $table->unsignedDecimal('mpc_discount', 4, 2)->default(0);
            $table->unsignedDecimal('vpc_discount', 4, 2)->default(0);
            $table->text('prices')->nullable();
            $table->timestamps();
    
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('action_roles', function (Blueprint $table) {
            $table->unsignedInteger('action_id');
            $table->unsignedInteger('role_id');
    
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('no action')->onUpdate('no action');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_roles');
        Schema::dropIfExists('action_products');
        Schema::dropIfExists('actions');
    }
}
