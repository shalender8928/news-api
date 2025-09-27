<?php

namespace App\Services\NewsProviders;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class NewsApiService implements ProviderInterface
{
    protected Client $client;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://newsapi.org/v2/']);
        $this->apiKey = config('services.newsapi.key') ?? env('NEWSAPI_KEY');
    }

    public function fetch(array $params = []): array
    {
        if (!$this->apiKey) return [];

        $query = array_merge([
            'apiKey' => $this->apiKey,
            'language' => 'en',
            'pageSize' => 50,
        ], $params);
        
        try {
            $res = $this->client->get('top-headlines', ['query' => $query]);
            $data = json_decode($res->getBody()->getContents(), true);
        } catch (Throwable $e) {
            Log::error('NewsApiService error: '.$e->getMessage());
            return [];
        }

        $items = [];
        foreach (Arr::get($data,'articles',[]) as $a) {
            $items[] = [
                'external_id' => null,
                'title' => $a['title'] ?? '',
                'description' => $a['description'] ?? null,
                'content' => $a['content'] ?? null,
                'url' => $a['url'] ?? '',
                'url_to_image' => $a['urlToImage'] ?? null,
                'published_at' => $a['publishedAt'] ?? now()->toIso8601String(),
                'author' => $a['author'] ?? null,
                'category' => null,
                'raw' => $a,
            ];
        }

        return $items;
    }
}
