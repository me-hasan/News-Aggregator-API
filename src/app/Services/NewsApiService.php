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

    public function fetch()
    {
        // To be implemented tomorrow
    }
}
