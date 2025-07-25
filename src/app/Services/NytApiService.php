<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class NytApiService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.key');
    }

    public function fetchArticles()
    {
        $response = Http::get("https://api.nytimes.com/svc/topstories/v2/home.json", [
            'api-key' => $this->apiKey,
        ]);

        $data = $response->json();

        return collect($data['results'] ?? [])->map(function ($article) {
            return [
                'title' => $article['title'] ?? null,
                'summary' => $article['abstract'] ?? null,
                'content' => $article['abstract'] ?? null,
                'url' => $article['url'] ?? null,
                'image_url' => $article['multimedia'][0]['url'] ?? null,
                'source' => 'New York Times',
                'author' => $article['byline'] ?? null,
                'category' => $article['section'] ?? null,
                'published_at' => $article['published_date'] ?? null,
                'api_origin' => 'nyt',
            ];
        });
    }
}
