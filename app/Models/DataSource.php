<?php

namespace App\Models;

use App\Libs\DatabaseConnector\Column;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * Class DataSource
 * @package App\Models
 *
 * @property integer $data_source_id
 * @property integer $query_id
 * @property integer $connection_string_id
 * @property array|null $data_source_config
 * @property array|null $data_source_structure
 */
class DataSource extends Model {
    use HasFactory;

    protected $keyType = 'string';
    protected $primaryKey = 'data_source_id';
    protected $guarded = [];

    protected $casts = [
        'data_source_structure' => 'json'
    ];

    /**
     * @param      $queryId
     * @param      $connectionStringId
     * @param bool $forceReloadStructure
     *
     * @return DataSource
     * @throws \Exception
     */
    static function getFromQueryAndConnectionString($queryId, $connectionStringId) {
        try {
            DB::beginTransaction();
            $dataSource = DataSource::query()->firstOrCreate([
                'connection_string_id' => $connectionStringId,
                'query_id' => $queryId,
            ]);
            if (!$dataSource->data_source_structure) {
                $dataSource->loadStructure();
            }
            DB::commit();
            return $dataSource;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function loadStructure() {
        $query = Query::query()->findOrFail($this->query_id);
        $connectionString = ConnectionString::query()->findOrFail($this->connection_string_id);
        $conn = connectDatabase(
            $connectionString->connection_string_query_dialect,
            $connectionString->connection_string,
        );
        $result = $conn->query($query->query);
        $columns = $result->getColumns()->map(function (Column $column) {
            return $column->toArray();
        })->toArray();
        $this->data_source_structure = $columns;
        $this->save();
    }
}
