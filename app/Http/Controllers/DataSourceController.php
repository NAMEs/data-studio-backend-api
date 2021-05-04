<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataSourceRequest;
use App\Models\ConnectionString;
use App\Models\DataSource;
use App\Models\Query;
use App\Repository\DataSourceRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DataSourceController extends CrudController {
    /**
     * @var DataSourceRepository
     */
    protected $repository;

    public function __construct(DataSourceRepository $repository) {
        $this->repository = $repository;
    }

    public function getDataSourceFromConnStringAndQuery(GetDataSourceRequest $request, Query $query, ConnectionString $connectionString) {
        try {
            $bindParams = $request->getBindParams();
            $isDebug = $request->isDebug();

            $hash = md5($connectionString->connection_string . $query->query . json_encode($bindParams)) . '@data-source';
            $dataSource = DataSource::getFromQueryAndConnectionString(
                $query->query_id,
                $connectionString->connection_string_id,
            );
            if (!$isDebug && $items = Cache::get($hash)) {
                return [
                    'status' => 'OK',
                    'data'   => array_merge(
                        $dataSource->toArray(),
                        [
                            'data' => $items,
                        ]
                    ),
                    'cache'  => TRUE,
                ];
            }

            $conn = connectDatabase(
                $connectionString->connection_string_query_dialect,
                $connectionString->connection_string
            );
            $result = $conn->query($query->query, $bindParams);

            $items = $result->getRows();
            Cache::put($hash, $items, now()->addMinutes(60));
            return [
                'status' => 'OK',
                'data'   => array_merge(
                    $dataSource->toArray(),
                    [
                        'data' => $items,
                    ]
                ),
                'query' => $isDebug ? $result->getFinalQuery() : NULL,
                'cache'  => FALSE,
            ];
        } catch (\Exception $e) {
            return \response()->json([
                'status'       => 'NG',
                'message' => Str::ucfirst($e->getMessage()),
            ], 500);
        }
    }
}
