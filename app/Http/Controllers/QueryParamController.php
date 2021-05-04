<?php

namespace App\Http\Controllers;

use App\Repository\QueryParamRepository;

class QueryParamController extends CrudController {
    /**
     * @var QueryParamRepository
     */
    protected $repository;

    public function __construct(QueryParamRepository $repository) {
        $this->repository = $repository;
    }
}
