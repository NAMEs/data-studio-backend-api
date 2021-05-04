<?php

namespace App\Http\Controllers;

use App\Repository\DocumentRepository;

class DocumentController extends CrudController {
    /**
     * @var DocumentRepository
     */
    protected $repository;

    public function __construct(DocumentRepository $repository) {
        $this->repository = $repository;
    }
}
