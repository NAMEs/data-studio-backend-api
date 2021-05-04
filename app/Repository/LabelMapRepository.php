<?php

namespace App\Repository;

use App\Models\LabelMap;
use Illuminate\Database\Eloquent\Collection;
/**
 * @package App\Repository
 * @method LabelMap getOne(int $id)
 * @method LabelMap[]|Collection getMany($where = [], $limit = 1000)
 * @method LabelMap insertOne(array $data)
 * @method bool deleteOne(int $id)
 */
class LabelMapRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return LabelMap::query();
    }
}
