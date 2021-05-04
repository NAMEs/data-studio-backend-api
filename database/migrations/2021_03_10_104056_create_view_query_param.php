<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewQueryParam extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $view_query_param = file_get_contents(__DIR__ . '/../queries/view_query_param.sql');

        DB::statement("CREATE VIEW query_param AS ($view_query_param)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP VIEW IF EXISTS query_param");
    }
}
