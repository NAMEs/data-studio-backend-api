<?php


namespace App\Libs\DatabaseConnector\ClickHouse;


use App\Libs\DatabaseConnector\BigQuery\BigQueryDatabaseConnectorException;
use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\ConnectionInformation;
use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Client as ClickHouseClient;

class ClickHouseDatabaseConnector implements DatabaseConnector {
    const DEFAULT_PORT = 8123;

    const TYPE_MAP = [
        'UInt8'       => Column::TYPE_NUMBER,
        'UInt16'      => Column::TYPE_NUMBER,
        'UInt32'      => Column::TYPE_NUMBER,
        'UInt64'      => Column::TYPE_NUMBER,
        'UInt256'     => Column::TYPE_NUMBER,
        'Int8'        => Column::TYPE_NUMBER,
        'Int16'       => Column::TYPE_NUMBER,
        'Int32'       => Column::TYPE_NUMBER,
        'Int64'       => Column::TYPE_NUMBER,
        'Int128'      => Column::TYPE_NUMBER,
        'Int256'      => Column::TYPE_NUMBER,
        'Float32'     => Column::TYPE_NUMBER,
        'Float64'     => Column::TYPE_NUMBER,
        'Decimal'     => Column::TYPE_NUMBER,
        'Decimal32'   => Column::TYPE_NUMBER,
        'Decimal64'   => Column::TYPE_NUMBER,
        'Decimal128'  => Column::TYPE_NUMBER,
        'Decimal256'  => Column::TYPE_NUMBER,
        'String'      => Column::TYPE_STRING,
        'Fixedstring' => Column::TYPE_STRING,
        'Date'        => Column::TYPE_DATE_TIME,
        'Datetime'    => Column::TYPE_DATE_TIME,
        'Datetime64'  => Column::TYPE_DATE_TIME,
    ];
    /**
     * @var ClickHouseClient
     */
    private $connection;

    /**
     * PostgreSQLDatabaseConnector constructor.
     *
     * @param string $connectionString
     *
     * @throws ClickHouseDatabaseConnectorException
     */
    public function __construct(string $connectionString) {
        $this->connect($connectionString);
    }

    /**
     * @param string $connectionString
     *
     * @return DatabaseConnector
     * @throws ClickHouseDatabaseConnectorException
     */
    public function connect(string $connectionString): DatabaseConnector {
        $config = json_decode($connectionString, TRUE);
        if ($config === NULL) {
            throw new ClickHouseDatabaseConnectorException("Wrong json format");
        }
        try {
            if (empty($config['port'])) {
                $config['port'] = (string) self::DEFAULT_PORT;
            }
            $this->connection = new ClickHouseClient($config);
            if (!empty($config['database'])) {
                $this->connection->database($config['database']);
            }
        } catch (\Exception $e) {
            throw new ClickHouseDatabaseConnectorException(
                $e->getMessage(),
                $e->getCode(),
                $e->getPrevious(),
            );
        }
        return $this;
    }

    public function query(string $sql, array $bindParams = []): QueryResponse {
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
                $sql = preg_replace("/:{$existedBindParam}/", 'NULL', $sql);
                $escapedBindParams[$existedBindParam] = NULL;
            }
        }
        $stmt = $this->connection->select($sql, $escapedBindParams);
        return new ClickHouseQueryResponse(
            $stmt,
            $escapedBindParams,
        );
    }

    public function getInformation(): ConnectionInformation {
        return new ConnectionInformation('clickhouse', $this->connection->getServerVersion());
    }
}
