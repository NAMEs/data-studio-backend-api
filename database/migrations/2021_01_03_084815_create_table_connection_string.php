<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTableConnectionString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_string', function (Blueprint $table) {
            $table->uuid('connection_string_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('document_id');
            $table->string('connection_string_name');
            $table->enum('connection_string_query_dialect', ['clickhouse', 'pgsql', 'bigquery']);
            $table->text('connection_string');
            $table->jsonb('connection_string_information')->nullable();
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
        Schema::dropIfExists('connection_string');
    }
}
