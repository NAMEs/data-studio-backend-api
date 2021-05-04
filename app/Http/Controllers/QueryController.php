<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckSQLRequest;
use App\Http\Requests\GetManyRequest;
use App\Repository\QueryRepository;

class QueryController extends CrudController {
    /**
     * @var QueryRepository
     */
    protected $repository;

    public function __construct(QueryRepository $repository) {
        $this->repository = $repository;
    }

    public function getMany(GetManyRequest $request) {
        if (!$request->get('for_edit', FALSE)) {
            return parent::getMany($request);
        } else {
            $filter = $request->getFilter();
            return $this->repository->getManyForEdit($filter);
        }
    }

    /**
     * @param CheckSQLRequest $request
     * @param int             $connectionStringId
     *
     * @return array|string[]
     */
    public function checkSQL(CheckSQLRequest $request, $connectionStringId) {
        return $this->repository->check(
            $connectionStringId,
            $request->getSQL()
        );
    }
}
