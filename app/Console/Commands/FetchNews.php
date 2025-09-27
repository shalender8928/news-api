<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsIngestService;
use App\Services\NewsProviders\ProviderFactory;
use App\Services\NewsProviders\{GuardianService, NewsApiService, NytService};
use App\Enums\SourceKey;


class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from providers as store in DB';

    /**
     * Execute the console command.
     */
    public function handle(NewsIngestService $ingest, ProviderFactory $factory)
    {
        foreach (SourceKey::cases() as $source) {
            $this->info("Fetching {$source->value}...");

            $provider = $factory->make($source);
            $items = $provider->fetch();
            $count = $ingest->ingest($source, $items);

            $this->info("{$source->value}: imported {$count} items.");
        }
        return Command::SUCCESS;
    }
}
