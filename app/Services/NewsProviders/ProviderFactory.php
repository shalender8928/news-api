<?php
namespace App\Services\NewsProviders;

use App\Enums\SourceKey;
use InvalidArgumentException;

class ProviderFactory
{
    public function make(SourceKey $source): ProviderInterface
    {
        return match ($source) {
            SourceKey::NEWSAPI  => app(NewsApiService::class),
            SourceKey::GUARDIAN => app(GuardianService::class),
            SourceKey::NYT      => app(NytService::class),
            default => throw new InvalidArgumentException("Unknown provider: {$source->value}")
        };
    }
}
