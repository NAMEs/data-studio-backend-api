<?php


namespace App\Library;


use ClickHouseDB\Client;

class ClickHouseDB {
    static function getConnection(): Client {
        return app(Client::class);
    }
}
