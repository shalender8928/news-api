<?php

namespace App\Services\NewsProviders;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class NytService implements ProviderInterface
{
    protected Client $client;
    protected ?string $key;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.nytimes.com/svc/']);
        $this->key = config('services.nyt.key') ?? env('NYT_KEY');
    }

    public function fetch(array $params = []): array
    {
        if (!$this->key) return [];

        try {
            $res = $this->client->get('topstories/v2/home.json', [
                'query' => ['api-key' => $this->key]
            ]);
            $data = json_decode($res->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            \Log::error('NytService error: '.$e->getMessage());
            return [];
        }

        $items = [];
        foreach (Arr::get($data,'results',[]) as $r) {
            $items[] = [
                'external_id' => $r['uri'] ?? null,
                'title' => $r['title'] ?? '',
                'description' => $r['abstract'] ?? null,
                'content' => $r['abstract'] ?? null,
                'url' => $r['url'] ?? '',
                'url_to_image' => collect($r['multimedia'] ?? [])->first()['url'] ?? null,
                'published_at' => $r['published_date'] ?? now()->toIso8601String(),
                'author' => $r['byline'] ?? null,
                'category' => $r['section'] ?? null,
                'raw' => $r,
            ];
        }

        return $items;
    }
}
