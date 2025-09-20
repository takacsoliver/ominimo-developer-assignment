# Ominimo Blog

A simple blog application developed for Ominimo as part of a developer interview assignment

## Quick Start

### Prerequisites
- Docker and Docker Compose
- Make (optional, for convenience commands)

### Setup

0. **Make .env file:**
   ```bash
   cp .env.example .env
   ```

1. **Start the development environment:**
   ```bash
   make dev-build
   ```
   Or without Make:
   ```bash
   docker compose -f docker-compose.dev.yml up --build
   ```

2. **Run database migrations and seed:**
   ```bash
   make fresh
   ```
   Or manually:
   ```bash
   docker exec -it ominimo-blog-app-dev php artisan migrate:fresh --seed
   ```

3. **Access the application:**
   - Blog: http://localhost:8000

### Default Credentials

- **Admin User:** admin@example.com / SuperSecret11

### Available Commands

```bash
# Development
make dev              # Start development environment (detached)
make dev-build        # Start development environment with build
make stop-dev         # Stop development environment

# Production
make prod             # Start production environment (detached)
make prod-build       # Start production environment with build
make stop-prod        # Stop production environment

# Database
make migrate          # Run migrations
make seed             # Run seeders
make fresh            # Fresh migration + seed

# Testing & Utilities
make test             # Run tests
make shell            # Access development container shell
make shell-prod       # Access production container shell
make logs             # View development logs
make logs-prod        # View production logs
make clean            # Clean up containers and volumes
```

### Features

- **Authentication:** User registration, login, password reset
- **Posts:** Create, edit, delete blog posts
- **Comments:** Add comments to posts (latest first)
- **Admin Panel:** Admin users can delete any post/comment
- **Responsive Design:** Mobile-friendly interface
- **Hot Reload:** Frontend changes update instantly
- **Docker:** Complete containerized development environment

### Tech Stack

- **Backend:** Laravel 12, Octane, Inertia.js
- **Frontend:** Vue.js 3, Tailwind CSS
- **Database:** MariaDB
- **Cache:** Redis
- **Container:** Docker, Docker Compose