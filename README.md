# News Aggregator Backend (Laravel 12)
A Laravel-based API service for fetching, storing, and serving articles from external sources such as News Api's using the NewsAPI, Guardian and New York Times APIs.

## Requirements
- PHP (compatible with Laravel 12)
- Composer
- MySQL
- API keys: NewsAPI, The Guardian, NYT (put in .env)

## Setup
1. Clone repo https://github.com/shalender8928/news-api.git
2. cd news-api
3. `composer install`
4. Copy `.env.example` -> `.env` and set DB and API keys `NEWSAPI_KEY`, `GUARDIAN_KEY`, `NYT_KEY`
5. `php artisan key:generate`
6. `php artisan migrate`
8. Manual fetch: `php artisan news:fetch`
9. Or schedule: set cron `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`

## API Endpoints
- `GET /api/ping` — health
- `GET /api/v1/articles` — list articles (params: `q`, `source`, `category`, `author`, `page`, `per_page`)
- Source and Category data can be get using `meta` API below.
- `GET /api/v1/articles/{id}` — get single article
- `GET /api/v1/meta` — list sources & categories

## Notes
- Open API (no authentication) per assignment requirement.
- Services follow ProviderInterface; add more providers by implementing the interface and calling ingest with the source key.
- For production: add caching, rate-limits, and error-alerting.
- `/api/ping` is a lightweight health check endpoint.
- Articles are fetched from external providers and stored locally.
- Pagination follows Laravel's default structure with meta and links.

## Important
- Please create a `.env.testing` file and configure a separate database for testing purposes only.  
  This prevents wiping out your local/dev database during `php artisan test`.  

Example `.env.testing` (using in-memory SQLite):
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

- When running tests, Laravel automatically uses `.env.testing`. You do not need to run `php artisan migrate:fresh --env=testing` manually, because migrations are handled inside the test runner.

- To run the full test suite:
```bash
php artisan test
```

## Author

Shalender Kumar  

## Links

- Blog：[https://www.codinghelpsolutions.com/](https://www.codinghelpsolutions.com/)
- GitHub：[https://github.com/shalender8928](https://github.com/shalender8928)
- LinkedIn：[https://www.linkedin.com/in/shalender8928/](https://www.linkedin.com/in/shalender8928/)


## License

news-api is open-sourced software licensed under the MIT license.