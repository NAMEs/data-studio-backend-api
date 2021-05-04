<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTablePage extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('page', function (Blueprint $table) {
            $table->uuid('page_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('document_id');
            $table->string('page_name')->nullable();
            $table->jsonb('page_setting')->default('{}');
            $table->timestamps();

            $table->foreign('document_id')->references('document_id')->on('document');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('element', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });
        Schema::table('connection_string', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });
        Schema::table('query', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });
        Schema::dropIfExists('page');
    }
}
