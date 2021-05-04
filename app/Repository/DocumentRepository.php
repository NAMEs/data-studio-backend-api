<?php

namespace App\Repository;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * @package App\Repository
 * @method Document[]|Collection getMany($where = [], $limit = 1000)
 * @method bool deleteOne(string $id)
 */
class DocumentRepository extends BaseRepository
{
    private $pageRepository;
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): Builder
    {
        return Document::query();
    }

    public function getOne($id)
    {
        $documentResult = DB::select("
            SELECT *,
            (
               SELECT json_agg(row_to_json(p))
               FROM page p
               WHERE p.document_id = document.document_id
            ) AS pages,
            (
                SELECT json_agg(row_to_json(q))
                FROM query q
                WHERE q.document_id = document.document_id
            ) AS queries,
            (
               SELECT json_agg(row_to_json(ct))
               FROM connection_string ct
               WHERE ct.document_id = document.document_id
            ) AS connection_strings,
            (
               SELECT json_agg(row_to_json(c))
               FROM color_map c
               WHERE c.document_id = document.document_id
            ) AS colors,
            (
               SELECT json_agg(row_to_json(l))
               FROM label_map l
               WHERE l.document_id = document.document_id
            ) AS labels
            FROM document
            WHERE document.document_id = ?;", [$id]);
        if (count($documentResult) < 1) {
            abort(404);
        }
        $document = (array) $documentResult[0];
        $jsonFields = [
            'pages',
            'queries',
            'connection_strings',
            'colors',
            'labels'
        ];
        foreach ($jsonFields as $field) {
            $document[$field] = empty($document[$field]) ? [] : json_decode($document[$field]);
        }
        return $document;
    }

    public function insertOne(array $data)
    {
        $document = parent::insertOne($data);
        $page = $this->pageRepository->insertOne([
            'document_id' => $document->document_id,
            'page_name' => 'Page 1',
        ]);
        return $document;
    }
}
