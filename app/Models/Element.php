<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Element
 * @package App\Models
 *
 * @property int $query_id
 * @property int $connection_string_id
 * @property int $element_id
 * @property string $element_type
 * @property array $element_config
 * @property array $element_style_position
 */
class Element extends Model {
    use HasFactory;

    protected $keyType = 'string';

    protected $primaryKey = 'element_id';

    protected $guarded = [];



    protected $casts = [
        'element_config' => 'json',
        'element_style_position' => 'json',
    ];
}
