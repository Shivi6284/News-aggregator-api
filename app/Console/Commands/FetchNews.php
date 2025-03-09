<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Illuminate\Console\Command;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store the latest news articles from external APIs';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(ArticleService $articleService)
    {
        $this->info('Fetching latest news...');
        $articleService->fetchNewsFromAPIs();
        $this->info('News articles updated successfully!');
    }
}
