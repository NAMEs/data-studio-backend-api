<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateFunctionsToGetDateRange extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $function_get_date_range_from_name_sql = file_get_contents(__DIR__ . '/../queries/function_get_date_range_from_name.sql');
        $function_get_date_range_from_time_ago = file_get_contents(__DIR__ . '/../queries/function_get_date_range_from_time_ago.sql');
        DB::statement($function_get_date_range_from_name_sql);
        DB::statement($function_get_date_range_from_time_ago);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP FUNCTION IF EXISTS get_date_range_from_name(date_range_name text)");
        DB::statement("DROP FUNCTION IF EXISTS get_date_range_from_time_ago(year integer, month integer, day integer, hour integer, minute integer, second integer)");
    }
}
