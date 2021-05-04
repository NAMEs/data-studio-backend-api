<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateColorMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_map', function (Blueprint $table) {
            $table->uuid('color_map_id')->primary()->default(DB::raw('uuid_generate_v4()'));;
            $table->uuid('document_id');
            $table->string('color_map_key');
            $table->string('color_map_value');
            $table->uuid('page_id');
            $table->foreign('page_id')->references('page_id')->on('page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_map');
    }
}
