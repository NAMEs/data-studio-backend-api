<?php

namespace App\Http\Controllers;

use App\Repository\ColorMapRepository;

class ColorMapController extends CrudController {
    /**
     * @var ColorMapRepository
     */
    protected $repository;

    public function __construct(ColorMapRepository $repository) {
        $this->repository = $repository;
    }
}
