<?php


namespace App\Libs\DatabaseConnector\PostgreSQL;


use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\QueryResponse;
use ClickHouseDB\Statement;
use Illuminate\Support\Collection;
use PDOStatement, PDO;

class PostgreSQLQueryResponse implements QueryResponse {

    /**
     * @var PDOStatement
     */
    private $stmt;

    /**
     * @var array
     */
    private $bindParams;

    /**
     * PostgreSQLQueryResponse constructor.
     *
     * @param PDOStatement $stmt
     * @param array        $bindParams
     */
    function __construct(PDOStatement $stmt, array $bindParams) {
        $this->stmt = $stmt;
        $this->bindParams = $bindParams;
    }

    private function convertType($nativeType): string {
        if (!empty(PostgreSQLDatabaseConnector::TYPE_MAP[$nativeType])) {
            return PostgreSQLDatabaseConnector::TYPE_MAP[$nativeType];
        }
        return Column::TYPE_OTHERS;
    }

    public function getColumns(): Collection {
        $columns = collect();
        $columnCount = $this->stmt->columnCount();
        for ($i = 0; $i < $columnCount; $i++) {
            $meta = $this->stmt->getColumnMeta($i);
            $columns->push(
                new Column(
                    $meta['name'],
                    $this->convertType($meta['native_type']),
                )
            );
        }
        return $columns;
    }

    public function getRows(int $limit = NULL): Collection {
        return collect($this->stmt->fetchAll(PDO::FETCH_ASSOC));
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
            $this->stmt->queryString
        );
    }


}
