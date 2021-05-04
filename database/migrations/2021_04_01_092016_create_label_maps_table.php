<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLabelMapsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('label_map', function (Blueprint $table) {
            $table->uuid("label_map_id")->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('document_id');
            $table->uuid('page_id');
            $table->string('label_map_key');
            $table->text('label_map_value');
            $table->timestamps();

            $table->foreign('page_id')->references('page_id')->on('page');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('label_map');
    }
}
