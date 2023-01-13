<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateExpressPostsTables
 */
class CreateExpressPostsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_express_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->string('express_post_type', 50);
            $table->string('shipment_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->longText('pdf_label')->nullable();
            $table->longText('pdf_pickup')->nullable();
            $table->string('status', 100);
            $table->dateTime('picked_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index(['express_post_type', 'status']);
    
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::create('document_takeovers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->string('name');
            $table->dateTime('picked_at');
    
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('no action')->onUpdate('no action');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_takeovers');
        Schema::dropIfExists('document_express_posts');
    }
}
