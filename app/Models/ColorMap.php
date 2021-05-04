<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ColorMap
 * @package App\Models
 * @property int color_map_id
 * @property string color_map_key
 * @property string color_map_value
 */
class ColorMap extends Model {
    use HasFactory;

    protected $keyType = 'string';

    protected $primaryKey = 'color_map_id';

    protected $guarded = [];
}
