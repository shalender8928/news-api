<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends Model
{
    use HasFactory;
    
    protected $fillable = ['key', 'title', 'api_name', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];
}
