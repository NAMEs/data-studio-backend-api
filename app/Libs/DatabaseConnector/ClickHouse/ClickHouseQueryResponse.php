<?php


namespace App\Libs\DatabaseConnector\ClickHouse;


use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\DatabaseConnectorException;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Statement;
use Illuminate\Support\Collection;
use ClickHouseDB\Exception\DatabaseException;
use Illuminate\Support\Facades\Log;

class ClickHouseQueryResponse implements QueryResponse {
    /**
     * @var Statement
     */
    private $stmt;

    /**
     * @var array
     */
    private $bindParams;

    /**
     * PostgreSQLQueryResponse constructor.
     *
     * @param Statement $stmt
     */
    function __construct($stmt, array $bindParams) {
        $this->stmt = $stmt;
        $this->bindParams = $bindParams;
    }

    private function convertType($nativeType): string {
        if (!empty(ClickHouseDatabaseConnector::TYPE_MAP[$nativeType])) {
            return ClickHouseDatabaseConnector::TYPE_MAP[$nativeType];
        }
        return Column::TYPE_OTHERS;
    }

    /**
     * @return Collection
     * @throws DatabaseConnectorException
     */
    public function getColumns(): Collection {
        try {
            $columns = collect();
            foreach ($this->stmt->rawData()['meta'] as $meta) {
                $columns->push(
                    new Column(
                        $meta['name'],
                        $this->convertType($meta['type'])
                    )
                );
            }
            return $columns;
        } catch (DatabaseException $exception) {
            Log::error($exception);
            throw new DatabaseConnectorException($exception->getMessage());
        }
    }

    public function getRows(int $limit = NULL): Collection {
        return collect($this->stmt->rows());
    }

    /**
     * @return string
     */
    public function getFinalQuery(): string {
        return $this->stmt->sql();
    }
}
