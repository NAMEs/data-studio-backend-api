<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddQueryCountUsedPageToQuery extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $queries = file_get_contents(__DIR__ . '/../queries/query_count_used_page.sql');
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
        Schema::table('query', function (Blueprint $table) {
            DB::statement('ALTER TABLE "query" DROP COLUMN query_count_used_page');
            DB::statement('DROP FUNCTION IF EXISTS count_element_use_query(_query_id bigint)');
        });
    }
}
