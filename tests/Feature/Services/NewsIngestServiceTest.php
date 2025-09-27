<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Services\NewsIngestService;
use App\Enums\SourceKey;
use App\Models\{Author, Category};
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsIngestServiceTest extends TestCase
{
    use RefreshDatabase; 
    public function test_ingests_article_with_source_key()
    {
        $service = app(NewsIngestService::class);

        $articles = [
            [
                'external_id'  => '123',
                'title'        => 'Integration Test',
                'url'          => 'http://test.com',
                'published_at' => now(),
                'author_id'    => Author::factory()->create()->id,
                'category_id'  => Category::factory()->create()->id,
            ]
        ];

        $count = $service->ingest(SourceKey::NEWSAPI, $articles);

        $this->assertEquals(1, $count);

        $this->assertDatabaseHas('articles', [
            'external_id' => '123',
            'source_key'  => SourceKey::NEWSAPI->value,
        ]);
    }

}
