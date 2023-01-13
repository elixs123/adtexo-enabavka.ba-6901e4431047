<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateBillingsTable
 */
class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country', 50);
            $table->string('country_id', 50)->nullable();
            $table->string('fund_source', 100)->nullable();
            $table->string('fund_source_id', 50)->nullable();
            $table->string('kif', 100)->nullable();
            $table->decimal('payed', 10)->default(0);
            $table->date('date_of_payment')->nullable();
            $table->unsignedInteger('person_id')->nullable();
            $table->timestamps();
            
            $table->index('kif');
    
            $table->foreign('country_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('fund_source_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            // $table->foreign('kif')->references('kif')->on('demands')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
}
