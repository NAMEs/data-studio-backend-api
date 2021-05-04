<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTableElement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element', function (Blueprint $table) {
            $table->uuid('element_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('page_id');
            $table->enum('element_type', ['chart', 'table', 'text', 'filter', 'shape' , 'image']);
            $table->uuid('connection_string_id')->nullable();
            $table->uuid('query_id')->nullable();
            $table->jsonb('element_config')->default('{}');
            $table->jsonb('element_style_position')->default(json_encode([
                'top' => 0,
                'left' => 0,
                'width' => 100,
                'height' => 100,
            ]));
            $table->smallInteger('element_style_elevation')->nullable()->default(1);
            $table->smallInteger('element_style_z_index')->nullable()->default(1);
            $table->string('element_style_background_color')->nullable()->default('transparent');
            $table->string('element_style_font_family')->nullable();
            $table->smallInteger('element_style_border_width')->nullable()->default(0);
            $table->smallInteger('element_style_border_radius')->nullable()->default(0);
            $table->string('element_style_border_color')->nullable()->default('black');
            $table->enum('element_style_border_style', ['solid', 'dotted'])->nullable()->default('solid');
            $table->string('element_style_text_color')->nullable()->default('black');
            $table->timestamps();

            $table->foreign('connection_string_id')->references('connection_string_id')->on('connection_string')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('query_id')->references('query_id')->on('query')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('page_id')->references('page_id')->on('page')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('element');
    }
}
