# news-api

## Introduction

This News REST API server is using the latest Laravel 10, MySQL and PHP 8. The environment setup is using Laravel `Sail` package with Docker containers for server, mysql, redis, etc.

See documentations for more details: https://laravel.com/docs/10.x/sail

## Installation

### 1: Clone Repository

### 2: Install Packages via Composer

```composer install```

### 3: Add Laravel "sail" Shell Alias

Add this line to your `~/.bashrc` file to create `sail` shortcut cmd for `vendor/bin/sail`:

```alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'```

Then to refresh:

```source ~/.bashrc```

### 4: Start Docker Containers

```sail up```

Use `-d` option to run in background:

```sail up -d```

### 5: Run Initial Migrations

```sail artisan migrate```

### 6: Create `.env` and Generate App Key

```
cp .env.example .env
sail artisan key:generate
```
Note: make sure to put your own API keys for newsapi, nytimes, and theguardian in the `.env`.

### 7: Test Running Server

If server is running properly then it should be accesible at:

```http://localhost:8000```
