<?php

namespace Tests\Feature;

use App\Enums\SourceKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;

class ApiArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_ping()
    {
        $this->getJson('/api/ping')->assertStatus(200)
        ->assertJson(['message'=>'API is working']);
    }

    public function test_articles_endpoint()
    {
        $response = $this->get('/api/v1/articles');

        $response->assertStatus(200);
    }

    public function test_articles_endpoint_returns_paginated_data()
    {
        Article::factory()->count(5)->create([
            'source_key' => SourceKey::NEWSAPI,
        ]);

        $resp = $this->getJson('/api/v1/articles');

        $resp->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'url'] // fields inside each article
            ],
            'links',
            'meta'
        ]);
    }

}
