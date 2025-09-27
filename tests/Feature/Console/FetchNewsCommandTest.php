<?php

namespace Tests\Feature\Console;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Services\NewsProviders\ProviderFactory;
use App\Enums\SourceKey;
use Mockery;
use App\Services\NewsProviders\ProviderInterface;

class FetchNewsCommandTest extends TestCase
{
    public function test_command_uses_factory_and_providers()
    {
        $fakeArticles = [
            ['title' => 'Test Title', 'url' => 'http://test.com']
        ];

        $mockFactory = Mockery::mock(ProviderFactory::class);

        // Fake providers returning mock data
        foreach (SourceKey::cases() as $source) {
            $mockProvider = Mockery::mock(ProviderInterface::class);
            $mockProvider->shouldReceive('fetch')->once()->andReturn($fakeArticles);

            $mockFactory->shouldReceive('make')
                ->with($source)
                ->andReturn($mockProvider);
        }

        $this->app->instance(ProviderFactory::class, $mockFactory);

        // Run the command
        Artisan::call('news:fetch');

        $this->assertStringContainsString('Fetching', Artisan::output());
    }
}
