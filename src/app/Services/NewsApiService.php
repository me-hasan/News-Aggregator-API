<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsApiService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetchArticles()
    {
        $response = Http::get("https://newsapi.org/v2/top-headlines", [
            'apiKey' => $this->apiKey,
            'language' => 'en',
            'pageSize' => 20,
        ]);

        $data = $response->json();

        return collect($data['articles'] ?? [])->map(function ($article) {
            return [
                'title' => $article['title'] ?? null,
                'summary' => $article['description'] ?? null,
                'content' => $article['content'] ?? null,
                'url' => $article['url'] ?? null,
                'image_url' => $article['urlToImage'] ?? null,
                'source' => $article['source']['name'] ?? null,
                'author' => $article['author'] ?? null,
                'category' => null,
                'published_at' => $article['publishedAt'] ?? null,
                'api_origin' => 'newsapi',
            ];
        });
    }
}
