<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClientsTable
 */
class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('type_id', 50);
            $table->string('jib', 13)->nullable();
            $table->string('pib', 12)->nullable();
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 191);
            $table->string('address', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country_id', 50)->nullable();
            $table->boolean('is_location');
            $table->string('location_code', 50)->unique()->nullable();
            $table->string('location_name', 191)->nullable();
            $table->string('location_type_id', 50)->nullable();
            $table->string('category_id', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->unsignedInteger('responsible_person_id')->nullable();
            $table->unsignedInteger('payment_person_id')->nullable();
            $table->unsignedInteger('salesman_person_id')->nullable();
            $table->unsignedInteger('supervisor_person_id')->nullable();
            $table->string('note', 255)->nullable();
            $table->unsignedSmallInteger('payment_period')->default(0);
            $table->string('payment_type', 50)->nullable();
            $table->unsignedTinyInteger('payment_discount')->default(0);
            $table->unsignedTinyInteger('discount_value1')->default(0);
            $table->unsignedTinyInteger('discount_value2')->default(0);
            $table->string('status', 20)->default('active');
            $table->timestamps();
    
            $table->index(['type_id']);
            $table->index(['country_id']);
            $table->index(['location_type_id']);
            $table->index(['category_id']);
            $table->index(['responsible_person_id']);
            $table->index(['payment_person_id']);
            $table->index(['salesman_person_id']);
            $table->index(['supervisor_person_id']);
            $table->index(['status']);
    
            $table->foreign('type_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('country_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('location_type_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('category_id')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('responsible_person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('payment_person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('salesman_person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('supervisor_person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
            $table->foreign('payment_type')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
            $table->foreign('status')->references('code')->on('code_books')->onDelete('no action')->onUpdate('no action');
        });
    
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['parent_id']);
            
            $table->foreign('parent_id')->references('id')->on('clients')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
