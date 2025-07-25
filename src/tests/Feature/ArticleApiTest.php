<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_articles()
    {
        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_filters_articles_by_source()
    {
        Article::factory()->create(['source' => 'BBC']);
        Article::factory()->create(['source' => 'CNN']);

        $response = $this->getJson('/api/articles/filter?source=BBC');

        $response->assertStatus(200);
        $response->assertJsonFragment(['source' => 'BBC']);
    }

    /** @test */
    public function it_searches_articles_by_keyword()
    {
        Article::factory()->create(['title' => 'AI changing the world']);
        Article::factory()->create(['title' => 'Football World Cup']);

        $response = $this->getJson('/api/articles/search?q=AI');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'AI changing the world']);
    }
}
