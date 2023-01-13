<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddTracesToDocumentExpressPosts
 */
class AddTracesToDocumentExpressPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->text('traces')->nullable()->after('pdf_pickup_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dropColumn('traces');
        });
    }
}
