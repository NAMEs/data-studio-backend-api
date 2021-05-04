<?php


namespace App\Libs\DatabaseConnector\BigQuery;


use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\ConnectionInformation;
use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Client as ClickHouseClient;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;

class BigQueryDatabaseConnector implements DatabaseConnector {
    const TYPE_MAP = [
        'INTEGER'   => Column::TYPE_NUMBER,
        'FLOAT'     => Column::TYPE_NUMBER,
        'STRING'    => Column::TYPE_STRING,
        'BOOLEAN'   => Column::TYPE_BOOLEAN,
        'DATETIME'  => Column::TYPE_DATE_TIME,
        'DATE'      => Column::TYPE_DATE_TIME,
        'TIME'      => Column::TYPE_DATE_TIME,
        'TIMESTAMP' => Column::TYPE_DATE_TIME,
    ];
    /**
     * @var BigQueryClient
     */
    private $connection;

    /**
     * @var Dataset
     */
    private $dataset;

    /**
     * BigQueryDatabaseConnector constructor.
     *
     * @param string $connectionString
     *
     * @throws BigQueryDatabaseConnectorException
     */
    public function __construct(string $connectionString) {
        $this->connect($connectionString);
    }

    /**
     * @param string $connectionString
     *
     * @return DatabaseConnector
     * @throws BigQueryDatabaseConnectorException
     */
    public function connect(string $connectionString): DatabaseConnector {
        $config = json_decode($connectionString, TRUE);
        if ($config === NULL) {
            throw new BigQueryDatabaseConnectorException("Wrong json format");
        }
        foreach ($config as &$value) {
            $value = str_replace('\n', PHP_EOL, $value);
        }
        try {
            $this->connection = new BigQueryClient([
                'keyFile' => $config
            ]);
            if (!empty($config['dataset'])) {
                $this->dataset = $this->connection->dataset($config['dataset']);
            }
            return $this;
        } catch (\Exception $e) {
            throw new BigQueryDatabaseConnectorException($e->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param array  $bindParams
     *
     * @return QueryResponse
     * @throws BigQueryDatabaseConnectorException
     */
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
                $sql = str_replace("@{$existedBindParam}", 'Null', $sql);
            }
        }
        $queryJobConfig = $this->connection->query($sql);
        $queryJobConfig->parameters($escapedBindParams);
        $result = $this->connection->runQuery($queryJobConfig);
        return new BigQueryQueryResponse(
            $result,
            $sql,
            $escapedBindParams,
        );
    }

    public function getInformation(): ConnectionInformation {
        return new ConnectionInformation('bigquery', $this->connection->getServiceAccount());
    }
}
