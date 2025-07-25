<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GuardianApiService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetchArticles()
    {
        $response = Http::get("https://content.guardianapis.com/search", [
            'api-key' => $this->apiKey,
            'show-fields' => 'trailText,body,thumbnail,byline',
            'page-size' => 20,
        ]);

        $data = $response->json();

        return collect($data['response']['results'] ?? [])->map(function ($article) {
            return [
                'title' => $article['webTitle'] ?? null,
                'summary' => $article['fields']['trailText'] ?? null,
                'content' => $article['fields']['body'] ?? null,
                'url' => $article['webUrl'] ?? null,
                'image_url' => $article['fields']['thumbnail'] ?? null,
                'source' => 'The Guardian',
                'author' => $article['fields']['byline'] ?? null,
                'category' => $article['sectionName'] ?? null,
                'published_at' => $article['webPublicationDate'] ?? null,
                'api_origin' => 'guardian',
            ];
        });
    }
}

