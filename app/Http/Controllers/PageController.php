<?php

namespace App\Http\Controllers;

use App\Repository\PageRepository;

class PageController extends CrudController {
    /**
     * @var PageRepository
     */
    protected $repository;

    public function __construct(PageRepository $repository) {
        $this->repository = $repository;
    }
}
