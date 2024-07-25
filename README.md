# Ignite Platform (Project Crimson Nova)

Welcome to our Support Ticketing & Forum Discussion Platform, a comprehensive system designed to streamline user interactions for support tickets and forum discussions. If you are interested in creating a new account or have inquiries, please reach out via [tjthavarshan@gmail.com](mailto:tjthavarshan@gmail.com).

## First Time Contributors

If you're new to the project, we highly recommend starting with our comprehensive [contribution guide](http://ignite.test/docs/CONTRIBUTING.html) to familiarize yourself with the project's contribution protocols and standards.

## API Documentation

Explore our extensive documentation for API, web interfaces, and a general usage guide available [here](https://ignite.test/docs). Located within the `docs` directory at the root of the codebase, this documentation is dynamically generated using Vitepress. It's deployed in tandem with the application. To update and regenerate the documentation after modifications, execute:

```bash
npm run docs:build
```

## Getting Started

### Prerequisites

Before initiating the installation process, please ensure you meet the server requirements outlined in the [official Laravel installation guide](https://laravel.com/docs/10.x/installation).

### Installation Guide

#### Clone the Repository

```bash
git clone https://github.com/Thavarshan/ignite.git
cd ignite
```

#### Installation Options

**Scripted Installation:**

```bash
chmod +x bin/setup.sh && bin/setup.sh
```

**Manual Installation:**

1. **Install Dependencies**

    ```bash
    composer install
    ```

2. **Configure Environment**

    Copy the example `.env` file and adjust the necessary configurations.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Ensure to correctly set up your database information within the `.env` file before proceeding.

3. **Database Migrations**

    Execute migrations to set up your database schema.

    ```bash
    php artisan migrate
    ```

4. **Database Seeding**

    Execute database seed to add data to your database.

    ```bash
    php artisan db:seed
    ```

5. **Front-end Assets**

    Compile the necessary front-end resources.

    ```bash
    npm ci && npm run build
    ```

6. **Serve Application**

    Start your local development server.

    ```bash
    php artisan serve
    ```

Access your server at <http://localhost:8000>.

### Quick Start Commands

For experienced developers looking for a quick recap of setup commands, refer to the following list:

```bash
git clone https://github.com/Thavarshan/ignite.git
cd ignite
composer install
cp .env.example .env
php artisan key:generate
# Ensure the database connection is configured in .env
php artisan migrate
php artisan serve
```

## Working with Queues

Our platform utilizes Laravel's queue system for background jobs, with `jobs` table storage and `supervisord` for job execution. Detailed instructions for managing queues, including manual job execution and dealing with failed jobs, are provided within the project documentation.

## Scheduled Jobs

Scheduled tasks are orchestrated using [Laravel's scheduler](https://laravel.com/docs/scheduling), with configurations for individual commands and intervals located in `app/Console/Kernel.php`. Noteworthy scheduled operations include daily database backups, stored within the server's *storage* directory.

## Security and Contributions

We take security seriously. For guidelines on reporting security vulnerabilities, please view [our security policy](https://github.com/Thavarshan/ignite/security/policy). Contributions are welcome; please refer to our contribution guide for more details on participating in the project's development.

## License

This platform is proprietary software and constitutes the intellectual property of Ignite. Unauthorized use, duplication, or modification of this material without express and written permission from the copyright owner is strictly prohibited.# Support Ticketing & Forum Discussion Platform (Project Ignite)

Welcome to our Support Ticketing & Forum Discussion Platform, a comprehensive system designed to streamline user interactions for support tickets and forum discussions. If you are interested in creating a new account or have inquiries, please reach out via [tjthavarshan@gmail.com](mailto:tjthavarshan@gmail.com).

## First Time Contributors

If you're new to the project, we highly recommend starting with our comprehensive [contribution guide](http://ignite.test/docs/CONTRIBUTING.html) to familiarize yourself with the project's contribution protocols and standards.

## API Documentation

Explore our extensive documentation for API, web interfaces, and a general usage guide available [here](https://ignite.test/docs). Located within the `docs` directory at the root of the codebase, this documentation is dynamically generated using Vitepress. It's deployed in tandem with the application. To update and regenerate the documentation after modifications, execute:

```bash
npm run docs:build
```

## Getting Started

### Prerequisites

Before initiating the installation process, please ensure you meet the server requirements outlined in the [official Laravel installation guide](https://laravel.com/docs/10.x/installation).

### Installation Guide

#### Clone the Repository

```bash
git clone https://github.com/Thavarshan/ignite.git
cd ignite
```

#### Installation Options

**Scripted Installation:**

```bash
chmod +x bin/setup.sh && bin/setup.sh
```

**Manual Installation:**

1. **Install Dependencies**

    ```bash
    composer install
    ```

2. **Configure Environment**

    Copy the example `.env` file and adjust the necessary configurations.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Ensure to correctly set up your database information within the `.env` file before proceeding.

3. **Database Migrations**

    Execute migrations to set up your database schema.

    ```bash
    php artisan migrate
    ```

4. **Database Seeding**

    Execute database seed to add data to your database.

    ```bash
    php artisan db:seed
    ```

5. **Front-end Assets**

    Compile the necessary front-end resources.

    ```bash
    npm ci && npm run build
    ```

6. **Serve Application**

    Start your local development server.

    ```bash
    php artisan serve
    ```

Access your server at <http://localhost:8000>.

### Quick Start Commands

For experienced developers looking for a quick recap of setup commands, refer to the following list:

```bash
git clone https://github.com/Thavarshan/ignite.git
cd ignite
composer install
cp .env.example .env
php artisan key:generate
# Ensure the database connection is configured in .env
php artisan migrate
php artisan serve
```

## Working with Queues

Our platform utilizes Laravel's queue system for background jobs, with `jobs` table storage and `supervisord` for job execution. Detailed instructions for managing queues, including manual job execution and dealing with failed jobs, are provided within the project documentation.

## Scheduled Jobs

Scheduled tasks are orchestrated using [Laravel's scheduler](https://laravel.com/docs/scheduling), with configurations for individual commands and intervals located in `app/Console/Kernel.php`. Noteworthy scheduled operations include daily database backups, stored within the server's *storage* directory.

## Security and Contributions

We take security seriously. For guidelines on reporting security vulnerabilities, please view [our security policy](https://github.com/Thavarshan/ignite/security/policy). Contributions are welcome; please refer to our contribution guide for more details on participating in the project's development.

## License

This platform is proprietary software and constitutes the intellectual property of Ignite. Unauthorized use, duplication, or modification of this material without express and written permission from the copyright owner is strictly prohibited.
