<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTableQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query', function (Blueprint $table) {
            $table->uuid('query_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('document_id');
            $table->string('query_name');
            $table->enum('query_dialect', ['clickhouse', 'pgsql', 'bigquery']);
            $table->text('query');
            $table->timestamps();

            $table->foreign('document_id')->references('document_id')->on('document');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('query');
    }
}
