<?php

namespace App\Repository;

use App\Models\QueryParam;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class QueryParamRepository
 * @package App\Repository
 * @method QueryParam getOne(int $id)
 * @method QueryParam[]|Collection getMany($where = [], $limit = 1000)
 * @method QueryParam insertOne(array $data)
 * @method QueryParam updateOne(int $id, array $data)
 * @method bool deleteOne(int $id)
 */
class QueryParamRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return QueryParam::query();
    }
}
