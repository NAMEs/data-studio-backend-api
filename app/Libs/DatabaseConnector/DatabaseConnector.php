<?php


namespace App\Libs\DatabaseConnector;


interface DatabaseConnector {
    function __construct(string $connectionString);

    /**
     * @param string $connectionString
     *
     * @return $this
     */
    public function connect(string $connectionString): DatabaseConnector;

    /**
     * @param string $sql
     * @param array  $bindParams
     *
     * @return QueryResponse
     */
    public function query(string $sql, array $bindParams = []): QueryResponse;

    /**
     * @return ConnectionInformation
     */
    public function getInformation(): ConnectionInformation;
}
