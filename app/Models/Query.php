<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Query
 * @package App\Models
 * @property string $query_dialect 'mysql' or 'pgsql' or ...
 * @property string $query
 * @property array $query_structure
 * @property integer $query_id
 * @property string[] $query_parameters
 */
class Query extends Model {
    use HasFactory;

    protected $keyType = 'string';

    protected $primaryKey = 'query_id';

    protected $guarded = [];

    protected $hidden = ['query'];

    protected $casts = [
        'query_parameters' => 'json',
        'query_structure' => 'json',
    ];
}
