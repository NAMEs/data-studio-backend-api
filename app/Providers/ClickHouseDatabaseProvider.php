<?php

namespace App\Providers;

use App\Library\ClickHouseDB;
use ClickHouseDB\Client;
use Illuminate\Support\ServiceProvider;

class ClickHouseDatabaseProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('database.connections.clickhouse');
            $client = new Client([
                'host' => $config['host'],
                'port' => $config['port'],
                'username' => $config['username'],
                'password' => $config['password'],
            ]);
            if ($database = $config['database']) {
                if (app()->isLocal()) {
                    $client->write("CREATE DATABASE IF NOT EXISTS \"{$database}\"");
                }
                $client->database($database);
            }
            return $client;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
