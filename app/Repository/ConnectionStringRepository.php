<?php

namespace App\Repository;


use App\Libs\DatabaseConnector\DatabaseConnector;
use App\Models\ConnectionString;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ConnectionStringRepository
 * @package App\Repository
 * @method ConnectionString getOne(int $id)
 * @method ConnectionString[]|Collection getMany($where = [], $limit = 1000)
 * @method ConnectionString updateOne(int $id, array $data)
 * @method bool deleteOne(int $id)
 */
class ConnectionStringRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return ConnectionString::query();
    }


    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|ConnectionString
     */
    public function insertOne(array $data) {
        return parent::insertOne($data)->makeVisible('connection_string');
    }

    /**
     * @param string                 $dialect
     * @param string                 $connectionString
     * @param DatabaseConnector|null $conn
     *
     * @return array
     * @throws \Exception
     */
    public function check(string $dialect, string $connectionString, DatabaseConnector &$conn = NULL) {
        $conn = connectDatabase($dialect, $connectionString);
        return [
            'DRIVER_NAME'    => $conn->getInformation()->getDialect(),
            'CLIENT_VERSION' => $conn->getInformation()->getVersion(),
        ];
    }

    public function getManyForEdit($where = [], $limit = 1000) {
        return $this->getQueryBuilder()
            ->where($where)
            ->limit($limit)
            ->get()
            ->makeVisible(['connection_string']);
    }
}
