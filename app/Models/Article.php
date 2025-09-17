<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'external_id','source_id','author_id','category_id',
        'title','description','content','url','url_to_image','published_at','raw'
    ];

    protected $casts = [
        'raw' => 'array',
        'published_at' => 'datetime',
    ];

    public function source() { 
        return $this->belongsTo(Source::class); 
    }
    
    public function author() { 
        return $this->belongsTo(Author::class); 
    }
    
    public function category() { 
        return $this->belongsTo(Category::class); 
    }
}
