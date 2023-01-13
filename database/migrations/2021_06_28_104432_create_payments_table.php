<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePaymentsTable
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50)->nullable();
            $table->string('service', 50)->nullable();
            $table->string('file');
            $table->dateTime('uploaded_at')->nullable();
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->text('config')->nullable();
            $table->unsignedDecimal('total_payments', 9, 3)->default(0);
            $table->unsignedDecimal('total_documents', 9, 3)->default(0);
            $table->string('status', 50);
            $table->timestamps();
    
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('payment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('document_id');
            $table->unsignedDecimal('amount', 9, 3)->default(0);
    
            $table->timestamps();
    
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('no action')->onUpdate('no action');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_items');
        Schema::dropIfExists('payments');
    }
}
