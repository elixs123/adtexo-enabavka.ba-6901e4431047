<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddColorsToCodeBooks
 */
class AddColorsToCodeBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('code_books', function (Blueprint $table) {
            $table->string('background_color', 8)->nullable()->after('type');
            $table->string('color', 8)->nullable()->after('background_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
