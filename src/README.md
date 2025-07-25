# News Aggregator API

A Laravel-based API service that aggregates news articles from multiple sources including NewsAPI, The Guardian, and New York Times.

## Table of Contents

- [Features](#features)
- [Docker Setup](#docker-setup)
- [Environment Configuration](#environment-configuration)
- [API Endpoints](#api-endpoints)
- [API Testing](#api-testing)
- [Data Fetching](#data-fetching)

## Features

- Aggregates news from multiple sources (NewsAPI, The Guardian, New York Times)
- RESTful API for accessing news articles
- Search and filter functionality
- Docker containerization for easy deployment
- PostgreSQL database for data storage

## Docker Setup

### Prerequisites

- Docker and Docker Compose installed on your system
- Git to clone the repository

### Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd news-aggregator-api
```

2. Create a network for the containers (if not already created):

```bash
docker network create wingsfinnet
```

3. Build and start the containers:

```bash
docker-compose up -d
```

This will start three containers:
- `dev-app-nginx`: Nginx web server (accessible at http://localhost:8081)
- `dev-app-php`: PHP-FPM service
- `composer`: Temporary container to install PHP dependencies

### Container Structure

- **Web Server**: Nginx serves the application on port 8081
- **PHP Service**: PHP 8.2 with PostgreSQL extensions
- **Composer**: Installs PHP dependencies

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

## API Endpoints

The API provides the following endpoints:

- `GET /api/articles`: List all articles (paginated)
- `GET /api/articles/{id}`: Get a specific article by ID
- `GET /api/articles/search?q={query}`: Search articles by keyword
- `GET /api/articles/filter?source={source}&category={category}&from={date}&to={date}`: Filter articles by source, category, and date range

## API Testing

You can test the API endpoints using tools like cURL, Postman, or any HTTP client.

### Example Requests

1. Get all articles:

```bash
curl http://localhost:8081/api/articles
```

2. Get a specific article:

```bash
curl http://localhost:8081/api/articles/1
```

3. Search for articles containing "climate":

```bash
curl http://localhost:8081/api/articles/search?q=climate
```

4. Filter articles by source and date range:

```bash
curl "http://localhost:8081/api/articles/filter?source=The%20Guardian&from=2024-01-01&to=2024-12-31"
```

## Data Fetching

The application includes a command to fetch news articles from the configured API sources:

```bash
docker exec dev-app-php php artisan fetch:news
```

This command fetches articles from:
- NewsAPI (top headlines)
- The Guardian
- New York Times (top stories)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

        