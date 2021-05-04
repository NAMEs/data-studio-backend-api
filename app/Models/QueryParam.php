<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class QueryParam
 * @package App\Models
 * @property string $query_param_key
 * @property string|null $query_param_value
 */
class QueryParam extends Model {
    use HasFactory;
    protected $table = 'query_param';
    protected $primaryKey = 'query_param_key';
    protected $keyType = 'string';
}
