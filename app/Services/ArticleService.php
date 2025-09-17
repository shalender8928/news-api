<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\DTOs\ArticleDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService
{
    protected ArticleRepository $repo;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param array $filters
     * @return array [data => [], meta => []]
     */
    public function list(array $filters = []): array
    {
        $page = $this->repo->search($filters);

        $data = collect($page->items())->map(function($a) {
            return (new ArticleDTO($a))->toArray();
        })->values()->all();

        return [
            'data' => $data,
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page' => $page->lastPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
            ],
        ];
    }

    public function save(array $payload)
    {
        return $this->repo->storeOrUpdate($payload);
    }
}
