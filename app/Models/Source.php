<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['key', 'title', 'api_name', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];
}
