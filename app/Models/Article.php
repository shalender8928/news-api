<?php

namespace App\Models;

use App\Enums\SourceKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'external_id',
        'source_key',
        'author_id',
        'category_id',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
        'raw'
    ];

    protected $casts = [
        'raw' => 'array',
        'published_at' => 'datetime',
        'source_key' => SourceKey::class,
    ];
    
    public function author() { 
        return $this->belongsTo(Author::class); 
    }
    
    public function category() { 
        return $this->belongsTo(Category::class); 
    }
}
