<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddMediasToActions
 */
class AddMediasToActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->string('photo', 191)->nullable()->after('total_discounted');
            $table->string('presentation', 191)->nullable()->after('photo');
            $table->string('technical_sheet', 191)->nullable()->after('presentation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn(['photo', 'presentation', 'technical_sheet']);
        });
    }
}
