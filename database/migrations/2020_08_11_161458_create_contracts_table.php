<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateContractsTable
 */
class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->unique();
            $table->unsignedInteger('total_qty');
            $table->unsignedInteger('total_bought');
            $table->text('note')->nullable();
            $table->string('status', 50);
            $table->timestamps();
    
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('contract_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('product_id');
            $table->decimal('discount', 8, 2)->default(0);
            $table->unsignedInteger('qty');
            $table->unsignedInteger('bought');
            $table->text('prices');
            $table->timestamps();
    
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('no action')->onUpdate('no action');
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
        Schema::dropIfExists('contract_products');
        Schema::dropIfExists('contracts');
    }
}
