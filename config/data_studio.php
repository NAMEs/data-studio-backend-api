<?php

return [
    'drivers' => [
        'clickhouse' => \App\Libs\DatabaseConnector\ClickHouse\ClickHouseDatabaseConnector::class,
        'pgsql' => \App\Libs\DatabaseConnector\PostgreSQL\PostgreSQLDatabaseConnector::class,
        'bigquery' => \App\Libs\DatabaseConnector\BigQuery\BigQueryDatabaseConnector::class,
    ]
];
