<?php


namespace App\Http\Controllers;

use App\Http\Requests\GetManyRequest;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CrudController extends Controller {
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * @param int|string $id
     *
     * @return Model
     */
    public function getOne($id) {
        return $this->repository->getOne($id);
    }

    /**
     * @return Collection|Model[]
     */
    public function getMany(GetManyRequest $request) {
        $filter = $request->getFilter();
        return $this->repository->getMany($filter);
    }

    /**
     * @param Request $request
     *
     * @return Model
     */
    public function insertOne(Request $request) {
        return $this->repository->insertOne($request->all());
    }

    /**
     * @param Request $request
     * @param int|string     $id
     *
     * @return Model
     */
    public function updateOne(Request $request, $id) {
        return $this->repository->updateOne($id, $request->all());
    }

    /**
     * @param int|string $id
     *
     * @return mixed
     */
    public function deleteOne($id) {
        return $this->repository->deleteOne($id);
    }
}
