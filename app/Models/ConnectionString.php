<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ConnectionString
 * @package App\Models
 * @property string $connection_string_query_dialect 'mysql' or 'pgsql' or ...
 * @property string $connection_string
 * @property string $connection_string_id
 * @property array $connection_string_information
 */
class ConnectionString extends Model {
    use HasFactory;

    protected $keyType = 'string';

    protected $primaryKey = 'connection_string_id';

    protected $guarded = [];

    protected $hidden = ['connection_string'];

    protected $casts = [
        'connection_string_information' => 'json'
    ];
}
