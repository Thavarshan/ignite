#!/bin/bash

# Function to check if a command is available
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check if PHP is installed, and install if not
if ! command_exists php; then
    echo "Installing PHP..."
    if command_exists brew; then
        brew install php
    elif command_exists apt-get; then
        sudo apt-get update
        sudo apt-get install -y php
    else
        echo "Unsupported package manager. Please install PHP manually."
        exit 1
    fi
    echo "PHP installed successfully."
fi

# Check if Composer is installed, and install if not
if ! command_exists composer; then
    echo "Installing Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    php -r "unlink('composer-setup.php');"
    echo "Composer installed successfully."
fi

# Check if Node.js and npm are installed, and install if not
if ! command_exists node || ! command_exists npm; then
    echo "Installing Node.js and npm..."
    if command_exists brew; then
        brew install node
    elif command_exists apt-get; then
        curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
        sudo apt-get install -y nodejs
    else
        echo "Unsupported package manager. Please install Node.js and npm manually."
        exit 1
    fi
    echo "Node.js and npm installed successfully."
fi

# Check if Docker is installed
if ! command_exists docker; then
    echo "Docker not found. Please install Docker manually."
    exit 1
fi

# Check if Docker Compose is installed
if ! command_exists docker-compose; then
    echo "Docker Compose not found. Please install Docker Compose manually."
    exit 1
fi

# Copy environment file for Docker
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Build and start Docker containers
docker-compose up -d --build

# Generate application key via Docker
docker-compose exec app php artisan key:generate

# Create and migrate the database via Docker
docker-compose exec app php artisan migrate:fresh

# Seed the database via Docker
docker-compose exec app php artisan db:seed

# Set permissions for Laravel directories via Docker
docker-compose exec app chmod -R 775 storage bootstrap/cache
