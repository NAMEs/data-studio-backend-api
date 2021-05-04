<?php

namespace App\Repository;


use App\Models\DataSource;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DataSourceRepository
 * @package App\Repository
 * @method DataSource getOne(int $id)
 * @method DataSource[]|Collection getMany($where = [], $limit = 1000)
 * @method DataSource insertOne(array $data)
 * @method DataSource updateOne(int $id, array $data)
 * @method bool deleteOne(int $id)
 */
class DataSourceRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return DataSource::query();
    }
}
