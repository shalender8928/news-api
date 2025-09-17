<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\Source;

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
        $source = Source::firstOrCreate(
            ['key' => 'newsapi'],
            ['title' => 'NewsAPI', 'api_name' => 'NewsAPI', 'meta' => json_encode([])]
        );

        Article::factory()->count(5)->create([
            'source_id' => $source->id,
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
