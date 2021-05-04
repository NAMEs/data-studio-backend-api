<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTableDataSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_source', function (Blueprint $table) {
            $table->uuid('data_source_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->jsonb('data_source_structure')->nullable();
            $table->uuid('query_id')->nullable();
            $table->uuid('connection_string_id')->nullable();
            $table->timestamps();

            $table->foreign('query_id')->references('query_id')->on('query')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('connection_string_id')->references('connection_string_id')->on('connection_string')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_source');
    }
}
