<?php

namespace App\Services\NewsProviders;

interface ProviderInterface
{
    /**
     * Fetch normalized articles from provider.
     * Return array of normalized items:
     * [
     *   external_id, title, description, content, url, url_to_image, published_at, author, category, raw
     * ]
     *
     * @param array $params provider-specific params
     * @return array
     */
    public function fetch(array $params = []): array;
}
