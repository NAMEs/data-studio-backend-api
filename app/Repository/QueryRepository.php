<?php

namespace App\Repository;

use App\Libs\DatabaseConnector\Column;
use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Libs\DatabaseConnector\DatabaseConnectorException;
use App\Models\DataSource;
use App\Models\Query;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class QueryRepository
 * @package App\Repository
 * @method Query getOne(int $id)
 * @method Query[]|Collection getMany($where = [], $limit = 1000)
 * @method bool deleteOne(int $id)
 */
class QueryRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return Query::query();
    }

    /**
     * @return ConnectionStringRepository
     */
    public function getConnectionStringRepositiory() {
        return app(ConnectionStringRepository::class);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|Query
     */
    public function insertOne(array $data) {
        return parent::insertOne($data)->makeVisible('query');
    }

    function updateOne($id, array $data) {
        try {
            DB::beginTransaction();
            $query = parent::updateOne($id, $data);
            DataSource::query()->where([
                'query_id' => $query->id
            ])->delete();
            DB::commit();
            return $query;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int                    $connectionStringId
     * @param string                 $sql
     * @param DatabaseConnector|null $conn
     *
     * @return array|string[]
     * @throws DatabaseConnectorException
     */
    function check($connectionStringId, string $sql, DatabaseConnector &$conn = NULL) {
        $connectionString = $this->getConnectionStringRepositiory()->getOne($connectionStringId);
        $conn = connectDatabase(
            $connectionString->connection_string_query_dialect,
            $connectionString->connection_string
        );
        $result = $conn->query($sql);
        $columns = $result->getColumns()->map(function (Column $column) {
            return $column->toArray();
        })->toArray();
        return $columns;
    }


    public function getManyForEdit($where = [], $limit = 1000) {
        return $this->getQueryBuilder()
            ->where($where)
            ->limit($limit)
            ->get()
            ->makeVisible('query');
    }
}
