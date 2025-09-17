<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArticleRepository
{
    public function query(): Builder
    {
        return Article::with(['source','author','category']);
    }

    /**
     * Search & filter with pagination
     *
     * @param array $filters keys: q, source, category, author, from, to, per_page
     */
    public function search(array $filters = []): LengthAwarePaginator
    {
        $q = $this->query();

        if (!empty($filters['source'])) {
            $q->whereHas('source', fn($b) => $b->where('key', $filters['source']));
        }

        if (!empty($filters['category'])) {
            $q->whereHas('category', fn($b) => $b->where('name', $filters['category']));
        }

        if (!empty($filters['author'])) {
            $q->whereHas('author', fn($b) => $b->where('name', 'like', '%'.$filters['author'].'%'));
        }

        if (!empty($filters['from'])) {
            $q->where('published_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $q->where('published_at', '<=', $filters['to']);
        }

        if (!empty($filters['q'])) {
            $q->where(function($s) use ($filters) {
                $s->where('title', 'like', '%'.$filters['q'].'%')
                  ->orWhere('description', 'like', '%'.$filters['q'].'%')
                  ->orWhere('content', 'like', '%'.$filters['q'].'%');
            });
        }

        $perPage = $filters['per_page'] ?? 20;
        return $q->orderBy('published_at','desc')->paginate($perPage);
    }

    public function storeOrUpdate(array $data): Article
    {
        // uniqueness: external_id+source_id or url
        $query = Article::query();

        if (!empty($data['external_id'])) {
            $query->where('external_id', $data['external_id'])
                  ->where('source_id', $data['source_id']);
        } else {
            $query->where('url', $data['url']);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->update($data);
            return $existing;
        }

        return Article::create($data);
    }
}
