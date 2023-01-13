<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDemandsTable
 */
class CreateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country', 50);
            $table->string('country_id', 50)->nullable();
            $table->string('kif', 100);
            $table->string('binding_document', 100)->nullable();
            $table->string('document', 50)->nullable();
            $table->unsignedInteger('document_id')->nullable();
            $table->string('salesman_person', 50)->nullable();
            $table->unsignedInteger('person_id')->nullable();
            $table->string('client', 50)->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->date('date_of_document')->nullable();
            $table->date('date_of_payment')->nullable();
            $table->decimal('amount', 10)->default(0);
            $table->decimal('payed', 10)->default(0);
            $table->decimal('debt', 10)->default(0);
            $table->unsignedInteger('overdue_days')->default(0);
            $table->timestamps();
    
            $table->index('kif');
    
            $table->foreign('country_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade')->onUpdate('no action');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demands');
    }
}
