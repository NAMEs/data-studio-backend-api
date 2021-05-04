<?php


namespace App\Libs\DatabaseConnector\BigQuery;


use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\DatabaseConnectorException;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Statement;
use Google\Cloud\BigQuery\QueryResults;
use Google\Cloud\Core\Exception\GoogleException;
use Illuminate\Support\Collection;
use ClickHouseDB\Exception\DatabaseException;
use Illuminate\Support\Facades\Log;
use Google\Cloud\BigQuery\Bytes as BigQueryBytes;
use Google\Cloud\BigQuery\Date as BigQueryDate;
use Google\Cloud\Core\Int64 as BigQueryInt64;
use Google\Cloud\BigQuery\Time as BigQueryTime;
use Google\Cloud\BigQuery\Timestamp as BigQueryTimestamp;

class BigQueryQueryResponse implements QueryResponse {
    /**
     * @var QueryResults
     */
    private $stmt;

    /**
     * @var array
     */
    private $bindParams;

    /**
     * @var string
     */
    private $query;

    /**
     * PostgreSQLQueryResponse constructor.
     *
     * @param QueryResults $stmt
     * @param string       $query
     * @param array        $bindParams
     */
    function __construct(
        $stmt,
        string $query,
        array $bindParams
    ) {
        $this->stmt = $stmt;
        $this->query = $query;
        $this->bindParams = $bindParams;
    }

    /**
     * @return Collection
     * @throws DatabaseConnectorException
     */
    public function getColumns(): Collection {
        try {
            $columns = collect();
            $fields = $this->stmt->info()['schema']['fields'];
            foreach ($fields as $field) {
                $columns->push(
                    new Column(
                        $field['name'],
                        $this->convertType($field['type'])
                    )
                );
            }
            return $columns;
        } catch (DatabaseException $exception) {
            Log::error($exception);
            throw new DatabaseConnectorException($exception->getMessage());
        }
    }

    /**
     * @param int|null $limit
     *
     * @return Collection
     * @throws GoogleException
     * @throws DatabaseConnectorException
     */
    public function getRows(int $limit = NULL): Collection {
        $data = collect();
        $count = 0;
//        $this->stmt->waitUntilComplete();
        if ($this->stmt->isComplete()) {
            $rows = $this->stmt->rows();
            foreach ($rows as $row) {
                foreach ($row as &$columnValue) {
                    if ($columnValue instanceof \DateTimeInterface) {
                        $columnValue = $columnValue->format('c');
                    } elseif (is_object($columnValue) && method_exists($columnValue, 'formatAsString')) {
                        $columnValue = $columnValue->formatAsString();
                    }
                }
                $data->push($row);
                $count++;
                if ($limit && $count >= $limit) {
                    break;
                }
            }
        } else {
            throw new DatabaseConnectorException('The query failed to complete');
        }
        return $data;
    }

    private function convertType($nativeType): string {
        if (!empty(BigQueryDatabaseConnector::TYPE_MAP[$nativeType])) {
            return BigQueryDatabaseConnector::TYPE_MAP[$nativeType];
        }
        return Column::TYPE_OTHERS;
    }

    public function getFinalQuery(): string {
        $params = array_map(
            function (string $param) {
                return ':' . $param;
            },
            array_keys($this->bindParams)
        );

        $replaces = array_map(
            function (string $param) {
                return "'" . str_replace("'", "\\'", $param) . "'";
            },
            array_values($this->bindParams)
        );

        return str_replace(
            $params,
            $replaces,
            $this->query
        );
    }
}
