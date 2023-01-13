<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddViewedAtToDocumentExpressPosts
 */
class AddViewedAtToDocumentExpressPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dateTime('viewed_at')->nullable()->after('delivered_at');
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
            $table->dropColumn('viewed_at');
        });
    }
}
