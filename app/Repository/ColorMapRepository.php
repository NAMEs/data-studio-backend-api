<?php

namespace App\Repository;

use App\Models\ColorMap;
use Illuminate\Database\Eloquent\Collection;

/**
 * @package App\Repository
 * @method ColorMap getOne(int $id)
 * @method ColorMap[]|Collection getMany($where = [], $limit = 1000)
 * @method ColorMap insertOne(array $data)
 * @method bool deleteOne(int $id)
 */
class ColorMapRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder {
        return ColorMap::query();
    }
}
