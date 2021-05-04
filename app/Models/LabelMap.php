<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LabelMap
 * @package App\Models
 * @property integer $label_map_id
 * @property string $page_id
 * @property string $label_map_key
 * @property string $label_map_value
 */
class LabelMap extends Model {
    use HasFactory;

    protected $keyType = 'string';

    protected $table = 'label_map';

    protected $primaryKey = 'label_map_id';

    protected $guarded = [];
}
