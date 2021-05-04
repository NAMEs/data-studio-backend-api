<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository {
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $primaryKey;

    /**
     * @return string
     */
    protected function getTable(): string {
        return $this->getQueryBuilder()->getModel()->getTable();
    }

    /**
     * @return string
     */
    protected function getPrimaryKey(): string {
        return $this->getQueryBuilder()->getModel()->getKeyName();
    }

    /**
     * @return Builder
     */
    abstract function getQueryBuilder(): Builder;

    /**
     * @param array $data
     *
     * @return Model
     */
    public function insertOne(array $data) {
        return $this->getQueryBuilder()->create($data);
    }

    /**
     * @param int|string $id
     *
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function getOne($id) {
        return $this->getQueryBuilder()->findOrFail($id);
    }

    /**
     * @param array $where
     * @param int   $limit
     *
     * @return Model[]|Collection
     */
    public function getMany($where = [], $limit = 1000) {
        return $this->getQueryBuilder()
            ->where($where)
            ->limit($limit)
            ->get();
    }

    /**
     * @param int|string   $id
     * @param array $data
     *
     * @return Model
     */
    public function updateOne($id, array $data) {
        $this->getQueryBuilder()->where(
            $this->getPrimaryKey(),
            $id
        )->update($data);

        return $this->getQueryBuilder()->where(
            $this->getPrimaryKey(),
            $id
        )->first();
    }

    /**
     * @param int|string $id
     *
     * @return mixed
     */
    public function deleteOne($id) {
        return $this->getQueryBuilder()
            ->where([
                $this->getPrimaryKey() => $id
            ])
            ->delete();
    }
}
