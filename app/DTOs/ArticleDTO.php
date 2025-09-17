<?php

namespace App\DTOs;

use App\Models\Article;

class ArticleDTO
{
    public ?int $id;
    public ?string $externalId;
    public string $title;
    public ?string $description;
    public ?string $content;
    public string $url;
    public ?string $image;
    public ?string $author;
    public ?string $category;
    public string $source;
    public string $publishedAt;

    public function __construct(Article $a)
    {
        $this->id = $a->id;
        $this->externalId = $a->external_id;
        $this->title = $a->title;
        $this->description = $a->description;
        $this->content = $a->content;
        $this->url = $a->url;
        $this->image = $a->url_to_image;
        $this->author = $a->author ? $a->author->name : null;
        $this->category = $a->category ? $a->category->name : null;
        $this->source = $a->source ? $a->source->key : '';
        $this->publishedAt = $a->published_at ? $a->published_at->toIso8601String() : now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->externalId,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image' => $this->image,
            'author' => $this->author,
            'category' => $this->category,
            'source' => $this->source,
            'published_at' => $this->publishedAt,
        ];
    }
}
