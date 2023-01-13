<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDocumentDocumentsTable
 */
class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('stock_id')->nullable();
            $table->text('buyer_data')->nullable();
            $table->text('shipping_data')->nullable();
			$table->string('type_id', 50)->default('order');
            $table->string('status', 50)->nullable()->default('draft');
            $table->string('internal_status', 20)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->string('payment_period', 50)->nullable();
            $table->string('delivery_type', 50)->nullable();
            $table->decimal('delivery_cost', 8, 2)->nullable()->default(0);
            $table->decimal('subtotal', 8, 2)->nullable()->default(0);
            $table->decimal('total', 8, 2)->nullable()->default(0);
            $table->string('currency', 5)->nullable()->default('KM');
            $table->date('date_of_order')->nullable();
            $table->date('date_of_delivery')->nullable();
            $table->date('date_of_payment')->nullable();
            $table->timestamps();

            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('no action')->onUpdate('no action');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
            $table->foreign('type_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('internal_status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('payment_type')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('payment_period')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('delivery_type')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });

        Schema::create('document_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('product_id');
            $table->string('code', 50)->nullable();
            $table->string('name', 255);
            $table->decimal('mpc', 8, 2);
            $table->decimal('vpc', 8, 2);
            $table->decimal('qty', 8, 2);
            $table->decimal('total', 8, 2);
            $table->timestamps();

            $table->unique(['document_id', 'product_id']);
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
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
        Schema::dropIfExists('documents');
		Schema::dropIfExists('document_products');
    }
}
