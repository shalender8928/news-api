<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsIngestService;
use App\Services\NewsProviders\NewsApiService;
use App\Services\NewsProviders\GuardianService;
use App\Services\NewsProviders\NytService;

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
    public function handle(NewsIngestService $ingest, NewsApiService $newsApi, GuardianService $guardian, NytService $nyt)
    {
        $this->info('Fetching NewsAPI...');
        $items = $newsApi->fetch();
        $count = $ingest->ingest('newsapi', $items);
        $this->info("NewsAPI: imported {$count} items.");

        $this->info('Fetching Guardian...');
        $items = $guardian->fetch();
        $count = $ingest->ingest('guardian', $items);
        $this->info("Guardian: imported {$count} items.");

        $this->info('Fetching NYT...');
        $items = $nyt->fetch();
        $count = $ingest->ingest('nyt', $items);
        $this->info("NYT: imported {$count} items.");

        return 0;
    }
}
