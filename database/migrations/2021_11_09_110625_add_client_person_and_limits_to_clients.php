<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddClientPersonAndLimitsToClients
 */
class AddClientPersonAndLimitsToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedInteger('client_person_id')->nullable()->after('phone');
            $table->unsignedDecimal('allowed_limit_in', 9, 3)->default(0)->after('lang_id');
            $table->unsignedDecimal('allowed_limit_outside', 9, 3)->default(0)->after('allowed_limit_in');
    
            $table->foreign('client_person_id')->references('id')->on('persons')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['client_person_id']);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['client_person_id', 'allowed_limit_in', 'allowed_limit_outside']);
        });
    }
}
