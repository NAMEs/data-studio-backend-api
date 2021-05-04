<?php

namespace App\Repository;

use App\Libs\DatabaseConnector\Column;
use App\Models\DataSource;
use App\Models\Element;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ElementRepository
 * @package App\Repository
 * @method Element getOne(int $id)
 * @method Element[]|Collection getMany($where = [], $limit = 1000)
 * @method Element insertOne(array $data)
 * @method bool deleteOne(int $id)
 */
class ElementRepository extends BaseRepository {

    /**
     * @inheritDoc
     */
    function getQueryBuilder(): Builder {
        return Element::query();
    }

    public function updateOne($id, array $data) {
        $element = $this->getOne($id);
        $element->fill($data);
        if ($element->element_type === 'chart') {
            if ($element->isDirty('connection_string_id')) {
                $element->query_id = NULL;
                $config = [
                    'label' => $element->element_config['label'],
                    'chartType' => $element->element_config['chartType'],
                ];
                $element->element_config = $config;
            } else if ($element->isDirty('query_id') && $element->query_id && $element->connection_string_id) {
                $config = $this->generateChartConfig(
                    $element->query_id,
                    $element->connection_string_id,
                    @$element->element_config['chartType'] ?? 'bar',
                    @$element->element_config['label'] ?? '',
                );
                $element->element_config = $config;
            }
        }
        $element->save();
        return $element;
    }

    /**
     * @param string   $id
     * @param array $config
     *
     * @return mixed
     * @throws \Exception
     */
    public function updateConfig($id, $config): Element {
        $element = $this->getOne($id);
        $oldConfig = $element->element_config;
        if (
            $element->element_type === 'chart'
            && !empty($config['chartType'])
            && $config['chartType'] != $oldConfig['chartType']
        ) {
            $label = $config['label'] ?? $oldConfig['chartType'];
            $chartType = $config['chartType'];
            $config = [
                'chartType' => $chartType,
                'label' => $label,
            ];
            if ($element->query_id && $element->connection_string_id) {
                $config = $this->generateChartConfig(
                    $element->query_id,
                    $element->connection_string_id,
                    $chartType,
                    $label
                );
            }
        } else {
            $config = array_merge($oldConfig, $config);
        }
        $element->element_config = $config;
        $element->save();
        return $element;
    }

    /**
     * @param integer $queryId
     * @param integer $connectionStringId
     * @param string  $chartType
     * @param string  $label
     *
     * @return array;
     * @throws \Exception
     */
    private function generateChartConfig($queryId, $connectionStringId, $chartType, $label = ''): array {
        $dataSource = DataSource::getFromQueryAndConnectionString(
            $queryId,
            $connectionStringId,
        );

        $datetimeColumns = [];
        $numericColumns = [];
        $categorizeColumns = [];

        foreach ($dataSource->data_source_structure as $column) {
            if (
                $column['type'] === Column::TYPE_STRING
                ||  strpos($column['name'], 'd_') === 0
            ) {
                $categorizeColumns[] = $column['name'];
            } elseif (
                $column['type'] === Column::TYPE_DATE_TIME
                ||  strpos($column['name'], 't_') === 0
                ||  strpos($column['name'], 'x_') === 0
            ) {
                $datetimeColumns[] = $column['name'];
            } elseif (
                $column['type'] === Column::TYPE_NUMBER
                ||  strpos($column['name'], 'y_') === 0
            ) {
                $numericColumns[] = $column['name'];
            }
        }

        $config = [
            'label' => $label,
            'chartType' => $chartType,
        ];
        switch ($chartType) {
            case 'pie':
                if (count($categorizeColumns) > 0) {
                    $config['dimension'] = $categorizeColumns[0];
                }
                if (count($numericColumns) > 0) {
                    $config['metric'] = $numericColumns[0];
                }
                break;
            case 'bar':
            case 'line':
                if (count($datetimeColumns) > 0) {
                    $config['dimension'] = $datetimeColumns[0];
                }
                if (count($numericColumns) > 0) {
                    $config['metric'] = $numericColumns;
                }
                if (count($categorizeColumns) > 0) {
                    $config['breakdownDimension'] = $categorizeColumns[0];
                }
                break;
            case 'table':
                $columns = array_map(function ($column) {
                    return [
                        'field'            => $column,
                        'displayType'      => 'string',
                        'color'            => 'white',
                        'width'            => 200,
                        'showNumber'       => FALSE,
                        'compactNumber'    => FALSE,
                        'decimalPrecision' => 2,
                    ];
                }, $categorizeColumns);
                $columns += array_map(function ($column) {
                    return [
                        'field'            => $column,
                        'displayType'      => 'number',
                        'color'            => 'white',
                        'width'            => 200,
                        'showNumber'       => FALSE,
                        'compactNumber'    => FALSE,
                        'decimalPrecision' => 2,
                    ];
                }, $numericColumns);
                $config['columns'] = array_map(function ($column) use ($columns) {
                    $column['width'] = 1 / count($columns);
                    return $column;
                }, $columns);
                break;
            case 'card':
                if (count($numericColumns) > 0) {
                    $config['column'] = $numericColumns[0];
                    $config['function'] = 'sum';
                }
                break;
        }
        return $config;
    }
}
