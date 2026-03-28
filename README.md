# Ignite

A community forum application built with Laravel 13, Inertia.js v2, and Vue 3.

## Features

- **Threads & Channels** — organized discussions with markdown support
- **Nested Replies** — threaded conversations with best answer marking
- **Likes & Subscriptions** — engage with threads and replies
- **Notifications** — configurable alerts for replies, mentions, and best answers
- **Content Moderation** — flag system with admin management
- **User Profiles** — public profiles with thread history
- **Two-Factor Authentication** — TOTP-based 2FA via Laravel Fortify
- **Admin Dashboard** — flag review, channel management, thread moderation
- **Full-Text Search** — search and filter threads by channel, popularity, or status
- **Dark Mode** — system-aware with manual toggle

## Tech Stack

- **Backend:** PHP 8.3+, Laravel 13, Fortify, Sanctum
- **Frontend:** Vue 3, Inertia.js v2, Tailwind CSS v4, TypeScript
- **Database:** PostgreSQL
- **Testing:** Pest 4
- **Tooling:** Vite, Pint, ESLint, Prettier, Wayfinder

## Requirements

- PHP 8.3+
- Composer
- Node.js & npm
- PostgreSQL

## Installation

```bash
git clone git@github.com:Thavarshan/ignite.git
cd ignite
composer run setup
```

This runs the full setup: installs dependencies, copies `.env`, generates an app key, runs migrations, and builds frontend assets.

Alternatively, step by step:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

## Configuration

Copy `.env.example` to `.env` and configure your database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ignite
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

## Development

```bash
composer run dev        # Starts PHP server, queue worker, log tail, and Vite
```

Or with SSR:

```bash
composer run dev:ssr
```

## Testing

```bash
composer run test       # Lint + type-check + Pest tests
```

Run a specific test:

```bash
php artisan test --compact --filter=TestName
```

Tests use SQLite in-memory by default (configured in `phpunit.xml`).

## Code Style

```bash
vendor/bin/pint              # Auto-fix PHP style
npm run lint                 # Auto-fix JS/Vue style
npm run format               # Prettier formatting
npm run types:check          # TypeScript type checking
```

## Project Structure

```
app/
├── Http/Controllers/        # Web and API controllers
├── Models/                  # Eloquent models
├── Notifications/           # Email and database notifications
├── Policies/                # Authorization policies
└── Services/                # Business logic services

resources/js/
├── pages/                   # Inertia page components
├── components/              # Reusable Vue components
├── layouts/                 # App and forum layouts
└── types/                   # TypeScript type definitions

tests/
├── Feature/                 # Feature/integration tests
└── Unit/                    # Unit tests
```

## License

MIT
