<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ConnectionStringCountUsedPageToConnectionString extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $queries = file_get_contents(__DIR__ . '/../queries/connection_string_count_used_page.sql');
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
        DB::statement('ALTER TABLE "connection_string" DROP COLUMN connection_string_count_used_document');
        DB::statement('DROP FUNCTION IF EXISTS count_element_use_connection(_connection_string_id bigint)');
    }
}
