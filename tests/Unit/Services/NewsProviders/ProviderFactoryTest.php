<?php

namespace Tests\Unit\Services\NewsProviders;

use Tests\TestCase;
use App\Services\NewsProviders\ProviderFactory;
use App\Services\NewsProviders\NewsApiService;
use App\Services\NewsProviders\GuardianService;
use App\Services\NewsProviders\NytService;
use App\Enums\SourceKey;

class ProviderFactoryTest extends TestCase
{
    public function test_resolves_newsapi_service()
    {
        $factory = app(ProviderFactory::class);
        $service = $factory->make(SourceKey::NEWSAPI);

        $this->assertInstanceOf(NewsApiService::class, $service);
    }

    public function test_resolves_guardian_service()
    {
        $factory = app(ProviderFactory::class);
        $service = $factory->make(SourceKey::GUARDIAN);

        $this->assertInstanceOf(GuardianService::class, $service);
    }

    public function test_resolves_nyt_service()
    {
        $factory = app(ProviderFactory::class);
        $service = $factory->make(SourceKey::NYT);

        $this->assertInstanceOf(NytService::class, $service);
    }
}
