<?php


namespace App\Libs\DatabaseConnector\PostgreSQL;

use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\ConnectionInformation;
use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Client as ClickHouseClient;
use PDO;

class PostgreSQLDatabaseConnector implements DatabaseConnector {
    const TYPE_MAP = [
        'int8' => Column::TYPE_NUMBER,
        'float8' => Column::TYPE_NUMBER,
        'integer' => Column::TYPE_NUMBER,
        'int' => Column::TYPE_NUMBER,
        'int4' => Column::TYPE_NUMBER,
        'numeric' => Column::TYPE_NUMBER,
        'float4' => Column::TYPE_NUMBER,
        'int2' => Column::TYPE_NUMBER,
        'text' => Column::TYPE_STRING,
        'char' => Column::TYPE_STRING,
        'varchar' => Column::TYPE_STRING,
        'timestamp' => Column::TYPE_DATE_TIME,
        'timestamptz' => Column::TYPE_DATE_TIME,
        'timetz' => Column::TYPE_DATE_TIME,
        'time' => Column::TYPE_DATE_TIME,
        'date' => Column::TYPE_DATE_TIME,
    ];

    /**
     * @var PDO
     */
    private $connection;

    /**
     * PostgreSQLDatabaseConnector constructor.
     *
     * @param string $connectionString
     *
     * @throws PostgreSQLDatabaseConnectorException
     */
    public function __construct(string $connectionString) {
        $this->connect($connectionString);
    }

    /**
     * @param string $connectionString
     *
     * @return DatabaseConnector
     * @throws PostgreSQLDatabaseConnectorException
     */
    public function connect(string $connectionString): DatabaseConnector {
        $config = json_decode($connectionString, TRUE);
        if ($config === NULL) {
            throw new PostgreSQLDatabaseConnectorException("Wrong json format");
        }
        try {
            $parts = [];
            foreach ($config as $key => $value) {
                $parts[] = "$key=$value";
            }
            $connectionString = implode(';', $parts);
            $this->connection = new PDO('pgsql:' . $connectionString);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            throw new PostgreSQLDatabaseConnectorException(
                $e->getMessage(),
                $e->getCode(),
                $e->getPrevious(),
            );
        }
        return $this;
    }

    /**
     * @param string $sql
     * @param array  $bindParams
     *
     * @return QueryResponse
     * @throws PostgreSQLDatabaseConnectorException
     */
    public function query(string $sql, array $bindParams = []): QueryResponse {
        try {
            $existedBindParams = getBindParamsFromQuery($sql);
            $escapedBindParams = [];
            foreach ($bindParams as $key => $value) {
                if (in_array($key, $existedBindParams)) {
                    $escapedBindParams[$key] = $value;
                }
            }
            $escapedBindParamsKeys = array_keys($escapedBindParams);
            foreach ($existedBindParams as $existedBindParam) {
                if (!in_array($existedBindParam, $escapedBindParamsKeys)) {
                    $escapedBindParams[$existedBindParam] = NULL;
                }
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($escapedBindParams);
            return new PostgreSQLQueryResponse(
                $stmt,
                $escapedBindParams,
            );
        } catch (\PDOException $e) {
            throw new PostgreSQLDatabaseConnectorException(
                $e->getMessage(),
            );
        }
    }

    public function getInformation(): ConnectionInformation {
        return new ConnectionInformation(
            'pgsql',
            $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION)
        );
    }
}
