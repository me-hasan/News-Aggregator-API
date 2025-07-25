<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Services\NewsApiService;
use App\Services\GuardianApiService;
use App\Services\NytApiService;

class FetchNewsArticles extends Command
{
    protected $signature = 'fetch:news';
    protected $description = 'Fetch news from multiple sources and store in DB';

    public function __construct(
        protected NewsApiService $newsApi,
        protected GuardianApiService $guardianApi,
        protected NytApiService $nytApi
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Fetching news...');

        $sources = [
            $this->newsApi->fetchArticles(),
            $this->guardianApi->fetchArticles(),
            $this->nytApi->fetchArticles(),
        ];

        foreach ($sources as $articles) {
            foreach ($articles as $data) {
                Article::updateOrCreate(
                    ['url' => $data['url']],
                    $data
                );
            }
        }

        $this->info('Articles fetched and stored successfully!');
    }
}

