# Ignite

Ignite is a community discussion platform built with Laravel 12, Inertia.js, and Vue 3. It provides a modern forum experience with channel-based conversations, threaded replies, and a polished user interface powered by Tailwind CSS.

## Requirements

- PHP 8.4+
- Node.js 22+
- Composer 2
- npm 10+
- SQLite (default for local development) or another database supported by Laravel

## Getting Started

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Start Vite dev server
npm run dev

# Start Laravel HTTP server
php artisan serve
```

By default the application uses SQLite. Create the database file before running migrations:

```bash
touch database/database.sqlite
```

Update `.env` if you plan to use MySQL, Postgres, or another driver.

## Quality Tooling

| Command                | Purpose                                            |
| ---------------------- | -------------------------------------------------- |
| `composer format`      | Format backend code with Pint                      |
| `composer lint`        | Lint backend code with Pint (dirty changes)        |
| `npm run lint`         | Lint and auto-fix Vue/TypeScript files with ESLint |
| `npm run format`       | Format frontend assets with Prettier               |
| `npm run format:check` | Check frontend formatting without modifying files  |
| `npm run lint:staged`  | Run lint-staged (used by the pre-commit hook)      |
| `php artisan test`     | Run the full PHPUnit test suite                    |

Pre-commit hooks (powered by Husky and lint-staged) ensure Pint, ESLint, and Prettier run against staged changes before every commit. To enable hooks locally, make sure your dependencies are installed (`npm install`).

## Continuous Integration

GitHub Actions runs linting, formatting checks, and the PHPUnit suite on every push and pull request targeting `main`. See [`.github/workflows/ci.yml`](.github/workflows/ci.yml) for details.

## Deployment

For Laravel Forge deployments:

1. Ensure the server has PHP 8.4, Node.js 22, and npm 10.
2. Update the deploy script to run:
    ```bash
    composer install --no-dev --prefer-dist --no-interaction
    php artisan migrate --force
    npm ci
    npm run build
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
3. Configure environment variables (`APP_KEY`, database credentials, queue connection, mail driver, etc.).
4. Point Forge's web directory to `public`.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for guidance on submitting issues and pull requests. By participating in this project you agree to abide by the [Code of Conduct](CODE_OF_CONDUCT.md).

## Security

If you discover a security vulnerability, please follow the process described in [SECURITY.md](SECURITY.md).

## License

This project is open-sourced under the [MIT license](LICENSE).
