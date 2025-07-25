````markdown
# News Aggregator API

A Laravel-based API service that aggregates news articles from multiple sources including NewsAPI, The Guardian, and New York Times.

## Table of Contents

- [Features](#features)
- [Docker Setup](#docker-setup)
- [Environment Configuration](#environment-configuration)
- [API Endpoints](#api-endpoints)
- [API Testing](#api-testing)
- [Data Fetching](#data-fetching)
- [Background Jobs with Supervisor](#background-jobs-with-supervisor)
- [Scheduling with Cron](#scheduling-with-cron)
- [Unit Testing](#unit-testing)

---

## Features

- Aggregates news from multiple sources (NewsAPI, The Guardian, New York Times)
- RESTful API for accessing news articles
- Search and filter functionality
- Docker containerization for easy deployment
- PostgreSQL database for data storage

---

## Docker Setup

### Prerequisites

- Docker and Docker Compose installed on your system
- Git to clone the repository

### Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd news-aggregator-api
````

2. Create a network for the containers (if not already created):

```bash
docker network create wingsfinnet
```

3. Build and start the containers:

```bash
docker-compose up -d
```

This will start three containers:

* `dev-app-nginx`: Nginx web server (accessible at [http://localhost:8081](http://localhost:8081))
* `dev-app-php`: PHP-FPM service
* `composer`: Temporary container to install PHP dependencies

---

## Environment Configuration

1. Copy the example environment file:

```bash
cp src/.env.example src/.env
```

2. Configure the following environment variables in the `.env` file:

```
APP_NAME="NewsAggregator"
APP_URL=http://localhost:8081
APP_KEY=<generate-app-key>

DB_CONNECTION=pgsql
DB_HOST=<database-host>
DB_PORT=5432
DB_DATABASE=<database-name>
DB_USERNAME=<database-username>
DB_PASSWORD=<database-password>

NEWS_API_KEY=<your-newsapi-key>
GUARDIAN_API_KEY=<your-guardian-api-key>
NYT_API_KEY=<your-nyt-api-key>
```

3. Generate an application key:

```bash
docker exec dev-app-php php artisan key:generate
```

4. Run database migrations:

```bash
docker exec dev-app-php php artisan migrate
```

---

## API Endpoints

The API provides the following endpoints:

* `GET /api/articles`: List all articles (paginated)
* `GET /api/articles/{id}`: Get a specific article by ID
* `GET /api/articles/search?q={query}`: Search articles by keyword
* `GET /api/articles/filter?source={source}&category={category}&from={date}&to={date}`: Filter articles by source, category, and date range

---

## API Testing

You can test the API endpoints using tools like cURL, Postman, or any HTTP client.

### Example Requests

```bash
curl http://localhost:8081/api/articles
curl http://localhost:8081/api/articles/1
curl http://localhost:8081/api/articles/search?q=climate
curl "http://localhost:8081/api/articles/filter?source=The%20Guardian&from=2024-01-01&to=2024-12-31"
```

---

## Data Fetching

The application includes a command to fetch news articles from the configured API sources:

```bash
docker exec dev-app-php php artisan fetch:news
```

This command fetches articles from:

* NewsAPI (top headlines)
* The Guardian
* New York Times (top stories)

---

## Background Jobs with Supervisor

To continuously run the `fetch:news` command in the background, Supervisor is used inside the PHP container.

### Supervisor Configuration

```ini
[program:news-fetcher]
command=php artisan fetch:news
autostart=true
autorestart=true
startsecs=5
stderr_logfile=/var/log/news-fetcher.err.log
stdout_logfile=/var/log/news-fetcher.out.log
```

### View Logs

```bash
# Check Supervisor process status
docker exec -it dev-app-php supervisorctl status

# View logs
docker exec -it dev-app-php tail -f /var/log/news-fetcher.out.log
```

---

## Scheduling with Cron

Alternatively, you can run the `fetch:news` command every hour using cron.

### Example: Cron Setup

1. Add the following line inside your PHP container’s crontab (you can use a shell script or Dockerfile for this):

```bash
0 * * * * docker exec dev-app-php supervisorctl start news-fetcher >> /var/log/cron-news.log 2>&1
```

2. To ensure cron is running in the container, you can use Supervisor to manage it too:

```ini
[program:cron]
command=/usr/sbin/crond -f -l 8
autostart=true
autorestart=true
```

This provides full control over background tasks and periodic execution.

---

## Unit Testing

Unit and feature tests are written using PHPUnit and Laravel's built-in test utilities.

### Running Tests

```bash
docker exec dev-app-php php artisan test
# or
docker exec dev-app-php vendor/bin/phpunit
```

### Example Feature Test

```php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_articles()
    {
        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
}
```

Your test suite is configured to use an in-memory SQLite database to ensure fast and isolated test runs.

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

