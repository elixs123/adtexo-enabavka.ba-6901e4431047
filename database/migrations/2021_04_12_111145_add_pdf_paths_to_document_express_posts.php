<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPdfPathsToDocumentExpressPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->string('pdf_label_path')->nullable()->after('pdf_label');
            $table->string('pdf_pickup_path')->nullable()->after('pdf_pickup');
        });
        
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dropColumn(['pdf_label', 'pdf_pickup']);
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
            $table->longText('pdf_label')->nullable()->before('pdf_label_path');
            $table->longText('pdf_pickup')->nullable()->before('pdf_pickup_path');
        });
    
        Schema::table('document_express_posts', function (Blueprint $table) {
            $table->dropColumn(['pdf_label_path', 'pdf_pickup_path']);
        });
    }
}
