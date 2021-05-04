<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Support\Str;

class Model extends LaravelModel {
    use HasFactory;

    public function getTable(): string {
        return $this->table ?? Str::snake(class_basename($this));
    }
}
