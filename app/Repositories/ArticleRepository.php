<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\SourceKey;

class ArticleRepository
{

    protected $articleModel;

    public function __construct(Article $articleModel)
    {
        $this->articleModel =  $articleModel;
    }

    public function query(): Builder
    {
        return $this->articleModel->with(['author','category']);
    }

    /**
     * Search & filter with pagination
     *
     * @param array $filters keys: q, source, category, author, from, to, per_page
     */
    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query();

        $query->when($filters['source'] ?? null, function ($q, $source) {
            // Ensure it's a valid enum before filtering
            if ($enum = SourceKey::tryFrom($source)) {
                $q->where('source_key', $enum->value);
            }
        })
        ->when($filters['category'] ?? null, function ($q, $category) {
            $q->whereRelation('category', 'name', $category);
        })
        ->when($filters['author'] ?? null, function ($q, $author) {
            $q->whereHas('author', fn($b) => $b->where('name', 'like', '%' . $author . '%'));
        })
        ->when($filters['from'] ?? null, function ($q, $from) {
            $q->where('published_at', '>=', $from);
        })
        ->when($filters['to'] ?? null, function ($q, $to) {
            $q->where('published_at', '<=', $to);
        })
        ->when($filters['q'] ?? null, function ($q, $searchTerm) {
            $q->where(function($s) use ($searchTerm) {
                $s->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%')
                ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        });

        $perPage = $filters['per_page'] ?? 20;
        return $query->orderBy('published_at','desc')->paginate($perPage);
    }

    public function storeOrUpdate(array $data): Article
    {
        // Determine the attributes to find the unique article by
        $uniqueAttributes = !empty($data['external_id'])
            ? ['external_id' => $data['external_id'], 'source_key' => $data['source_key']]
            : ['url' => $data['url']];

        return $this->articleModel->updateOrCreate($uniqueAttributes, $data);
    }

    public function find($id) {
        return $this->query()->findOrFail($id);
    }
}
