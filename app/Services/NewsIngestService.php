<?php

namespace App\Services;

use App\Enums\SourceKey;
use App\Models\{Author, Category};
use App\Repositories\ArticleRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB, Log};
use Throwable;

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
     * @param SourceKey $sourceKey e.g. SourceKey::NEWSAPI, SourceKey::GUARDIAN
     * @param array $items normalized provider items
     */
    public function ingest(SourceKey $sourceKey, array $items): int
    {
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
                    'external_id'   => $item['external_id'] ?? null,
                    'source_key'     => $sourceKey->value,
                    'author_id'     => $authorId,
                    'category_id'   => $categoryId,
                    'title'         => $item['title'] ?? '',
                    'description'   => $item['description'] ?? null,
                    'content'       => $item['content'] ?? null,
                    'url'           => $item['url'] ?? '',
                    'url_to_image'  => $item['url_to_image'] ?? null,
                    'published_at'  => $item['published_at'] ?? now(),
                    'raw'           => $item['raw'] ?? null,
                ];

                $this->repo->storeOrUpdate($payload);
                $count++;
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Ingest error: '.$e->getMessage());
        }

        return $count;
    }
}
