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
        $this->getJson('/api/ping')->assertStatus(200)->assertJson(['message'=>'ok']);
    }

    public function test_articles_endpoint_returns_paginated_data()
    {
        $source = Source::factory()->create(['key'=>'newsapi','title'=>'NewsAPI']);
        Article::factory()->count(5)->create(['source_id' => $source->id]);

        $resp = $this->getJson('/api/articles');
        $resp->assertStatus(200)
             ->assertJsonStructure(['data','meta']);
    }
}
