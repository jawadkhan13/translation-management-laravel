# Translation Management Service

This project is an API-driven translation management service built with Laravel, designed for scalability, security, and performance. It supports multiple locales, tagging for context, and provides CRUD operations for managing translations. The system ensures efficient performance, with response times of <200ms per request and <500ms for exporting large datasets (~1000 records).

---

## Table of Contents

1. [Technologies Used](#technologies-used)
2. [Repository Structure](#repository-structure)
3. [Prerequisites](#prerequisites)
4. [Getting Started](#getting-started)
   - [1. Clone the Repository](#1-clone-the-repository)
   - [2. Environment Configuration]
   (#2-environment-configuration)
   - [3. Docker Setup](#3-docker-setup)
5. [Running the Application](#running-the-application)

---

## Technologies Used

- **Backend:**
  - Laravel 11
  - Composer
  - MySQL
  - Docker

- **DevOps:**
  - Docker
  - Docker Compose
  - Nginx

## Repository Structure

- **backend/**: Laravel backend application.
- **nginx/**: Nginx configuration files.
- **docker-compose.yml**: Docker services configuration.
- **.gitignore**: Files and folders to ignore in Git.
- **README.md**: This documentation file.


## Prerequisites

Ensure the following dependencies are installed before proceeding:

- **[Git](https://git-scm.com/downloads)**: Version control.
- **[Docker](https://www.docker.com/get-started)**: Containerization.
- **[Docker Compose](https://docs.docker.com/compose/install/)**: Multi-container orchestration.
- **[Composer](https://getcomposer.org/download/)**: PHP dependency manager (if not using Docker for backend).

---


## Getting Started

### 1. Clone the Repository

Run the following commands in your terminal:

```bash
git clone https://github.com/jawadkhan13/translation-management-laravel.git
cd Translation-mangement-laravel
```

### 2. Environment Configuration

Navigate to the backend folder:

```bash
cd backend
```

Copy the example .env file:

```bash
cp .env.example .env
```


Update the .env file with necessary environment variables, particularly the database and API authentication details:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost
API_AUTH_TOKEN=vSI4ng4s18LMGhYTBsYOyLaBqLnNESpoONoEo4YWG8Q

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=tranlations
DB_USERNAME=root
DB_PASSWORD=3jbY8KXCK2WKAohqniA=

# Other environment variables...

```

Generate the application key (if not already set):

```bash
php artisan key:generate
```

### 3. Docker Setup

a. Build and Start Containers

```bash
docker-compose up --build -d
```
- -d: Runs containers in the background
- --build: Rebuilds the Docker images.

b. Check if Containers are Running

```bash
docker-compose ps
```
You should see the following containers in the Up state:

- backend 
- nginx_server 
- mysql
- phpmyadmin

## Running the Application

After the setup, ensure that all Docker containers are running, then:

- **Access the API:** Open http://localhost:8000/ in your browser

## Features
- **CRUD endpoints:** Endpoints for creating, updating, viewing, searching, and exporting translations.
- **Tag support:** Categorize translations by context (e.g., mobile, desktop, web).
- **Optimized JSON Export:** Efficiently exports translations for frontend apps.
- **Secure Authentication:** Token-based authentication system.
- **High Scalability:** Can handle 100K+ records efficiently.
- **Performance Optimizations:** Chunked queries and indexed database structure.
- **OpenAPI Documentation:** API documentation available in `openapi.yaml`.
- **Dockerized Setup:** Easy deployment via `docker-compose.yml`.
- **Test Coverage:** Includes unit, feature, and performance tests.
