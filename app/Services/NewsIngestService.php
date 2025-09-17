<?php

namespace App\Services;

use App\Models\Source;
use App\Models\Author;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NewsIngestService
{
    protected ArticleRepository $repo;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Ingest normalized items for a source key
     *
     * @param string $sourceKey e.g. 'newsapi', 'guardian', 'nyt'
     * @param array $items normalized provider items
     */
    public function ingest(string $sourceKey, array $items): int
    {
        $source = Source::where('key', $sourceKey)->first();
        if (!$source) {
            \Log::warning("Source {$sourceKey} not found");
            return 0;
        }

        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                // author
                $authorId = null;
                if (!empty($item['author'])) {
                    $author = Author::firstOrCreate(
                        ['name' => $item['author']],
                        ['slug' => Str::slug($item['author'])]
                    );
                    $authorId = $author->id;
                }

                // category
                $categoryId = null;
                if (!empty($item['category'])) {
                    $category = Category::firstOrCreate(['name' => $item['category']]);
                    $categoryId = $category->id;
                }

                $payload = [
                    'external_id' => $item['external_id'] ?? null,
                    'source_id' => $source->id,
                    'author_id' => $authorId,
                    'category_id' => $categoryId,
                    'title' => $item['title'] ?? '',
                    'description' => $item['description'] ?? null,
                    'content' => $item['content'] ?? null,
                    'url' => $item['url'] ?? '',
                    'url_to_image' => $item['url_to_image'] ?? null,
                    'published_at' => $item['published_at'] ?? now(),
                    'raw' => $item['raw'] ?? null,
                ];

                $this->repo->storeOrUpdate($payload);
                $count++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Ingest error: '.$e->getMessage());
        }

        return $count;
    }
}
