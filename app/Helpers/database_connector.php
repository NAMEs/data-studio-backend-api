<?php

use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Libs\DatabaseConnector\DatabaseConnectorException;

if (!function_exists('connectDatabase')) {
    /**
     * @param string $dialect
     * @param string $connectionString
     *
     * @return DatabaseConnector
     * @throws DatabaseConnectorException
     */
    function connectDatabase(string $dialect, string $connectionString): DatabaseConnector {
        if (!empty($Driver = config("data_studio.drivers.$dialect"))) {
            return new $Driver($connectionString);
        }
        throw new DatabaseConnectorException("\"$dialect\" is not supported");
    }
}
if (!function_exists('getBindParamsFromQuery')) {
    function getBindParamsFromQuery(string $query): array {
        preg_match_all('/(?:[^:]:|[^@]@)([a-zA-Z0-9_]+)/', $query, $matches);
        return array_map(function ($match) {
            return trim(trim($match), ':');
        }, $matches[1]);
    }
}
