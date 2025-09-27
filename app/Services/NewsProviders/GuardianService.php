<?php

namespace App\Services\NewsProviders;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class GuardianService implements ProviderInterface
{
    protected Client $client;
    protected ?string $key;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://content.guardianapis.com/']);
        $this->key = config('services.guardian.key') ?? env('GUARDIAN_KEY');
    }

    public function fetch(array $params = []): array
    {
        if (!$this->key) return [];

        $query = array_merge([
            'api-key' => $this->key,
            'show-fields' => 'headline,byline,thumbnail,body',
            'page-size' => 50,
        ], $params);

        try {
            $res = $this->client->get('search', ['query' => $query]);
            $data = json_decode($res->getBody()->getContents(), true);
        } catch (Throwable $e) {
            Log::error('GuardianService error: '.$e->getMessage());
            return [];
        }

        $items = [];
        foreach (Arr::get($data,'response.results',[]) as $r) {
            $fields = $r['fields'] ?? [];
            $items[] = [
                'external_id' => $r['id'],
                'title' => $r['webTitle'] ?? '',
                'description' => $fields['headline'] ?? null,
                'content' => $fields['body'] ?? null,
                'url' => $r['webUrl'] ?? '',
                'url_to_image' => $fields['thumbnail'] ?? null,
                'published_at' => $r['webPublicationDate'] ?? now()->toIso8601String(),
                'author' => $fields['byline'] ?? null,
                'category' => $r['sectionName'] ?? null,
                'raw' => $r,
            ];
        }

        return $items;
    }
}
