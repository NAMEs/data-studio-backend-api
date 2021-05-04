<?php

namespace App\Http\Controllers;

use App\Repository\LabelMapRepository;

class LabelMapController extends CrudController {
    /**
     * @var LabelMapRepository
     */
    protected $repository;

    public function __construct(LabelMapRepository $repository) {
        $this->repository = $repository;
    }
}
