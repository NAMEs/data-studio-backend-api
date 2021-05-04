<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Page
 * @package App\Models
 * @property string $page_id
 * @property string $page_title
 * @property string $page_description
 * @property array $page_setting
 */
class Page extends Model {
    use HasFactory;

    protected $primaryKey = 'page_id';

    protected $guarded = [];

    protected $keyType = 'string';

    protected $casts = [
        'page_setting' => 'json',
    ];
}
