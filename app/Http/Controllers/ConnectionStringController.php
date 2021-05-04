<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetManyRequest;
use App\Repository\ConnectionStringRepository;
use Illuminate\Http\Request;

class ConnectionStringController extends CrudController {
    /**
     * @var ConnectionStringRepository
     */
    protected $repository;

    public function __construct(ConnectionStringRepository $repository) {
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

    public function checkConnectionString(Request $request) {
        $connectionString = $request->get('connectionString', '');
        $dialect = $request->get('dialect', '');
        return $this->repository->check(
            $dialect,
            $connectionString
        );
    }
}
