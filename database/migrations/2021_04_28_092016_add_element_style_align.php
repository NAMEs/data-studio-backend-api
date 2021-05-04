<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElementStyleAlign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('element', function (Blueprint $table) {
            $table->enum('element_style_horizontal_align', ['left', 'center', 'right'])->nullable()->default('center');
            $table->enum('element_style_vertical_align', ['top', 'center', 'bottom'])->nullable()->default('center');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('element', function (Blueprint $table) {
            $table->dropColumn('element_style_vertical_align');
            $table->dropColumn('element_style_horizontal_align');
        });
    }
}
