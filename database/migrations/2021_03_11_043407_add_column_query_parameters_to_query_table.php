<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnQueryParametersToQueryTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $queries = file_get_contents(__DIR__ . '/../queries/columns_query_parameters.sql');
        $queries = explode('----', $queries);
        foreach ($queries as $query) {
            DB::statement($query);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement('ALTER TABLE "query" DROP COLUMN query_parameters');
        DB::statement('DROP FUNCTION IF EXISTS "get_params_from_query"(query text)');
    }
}
