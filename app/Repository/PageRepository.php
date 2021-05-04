<?php

namespace App\Repository;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * @package App\Repository
 * @method Page[]|Collection getMany($where = [], $limit = 1000)
 * @method bool deleteOne(string $id)
 */
class PageRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): Builder {
        return Page::query();
    }
    public function insertOne(array $data)
    {
        if (empty($data['page_setting'])) {
            $data['page_setting'] = [
                'width' => 1000,
                'height' => 1000,
                'bgColor' => 'white',
            ];
        }
        return parent::insertOne($data);
    }

    public function getOne($id) {
        $pageResult = DB::select(
            "SELECT *,
                (
                    SELECT json_agg(row_to_json(element)) FROM element WHERE element.page_id = page.page_id
                ) AS elements,
                (
                    SELECT json_agg(row_to_json(query_param)) FROM query_param WHERE query_param.page_id = page.page_id
                ) AS query_params
            FROM page
            WHERE page.page_id = ?; ",
            [$id]
        );
        if (count($pageResult) < 1) {
            abort(404);
        }
        $page = (array) $pageResult[0];

        $jsonFields = [
            'elements',
            'query_params',
            'page_setting',
        ];
        foreach ($jsonFields as $field) {
            $page[$field] = empty($page[$field]) ? [] : json_decode($page[$field]);
        }
        return $page;
    }
}
