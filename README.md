# Employee Management System for HR

A full-stack HR management application built with Vue.js and Laravel. Developed during a summer internship.

## Tech Stack

### Frontend
- **Vue.js 3.4** - Progressive JavaScript framework
- **Pinia** - State management (Vue 3 recommended)
- **Vue Router 4** - SPA routing
- **Axios** - HTTP client
- **Chart.js 4** - Data visualization
- **Bootstrap 5** - UI framework
- **SCSS** - CSS preprocessor
- **Vite** - Build tool and dev server

### Backend
- **Laravel 11** - PHP framework (requires PHP 8.2+)
- **JWT Auth** - Token authentication
- **MySQL** - Database
- **Spatie Permissions** - Role-based access control

### Development Tools
- **Vite** - Frontend build tool
- **Composer 2.x** - PHP dependency management
- **npm** - JavaScript package management
- **Laravel Pint** - Code style fixer

## Overview

HR solution for managing employee profiles, leave requests, skills tracking, training records, and performance evaluations with dashboard analytics.

## Features

- Employee CRUD operations with profiles
- Department management and visualization
- Leave request tracking
- Skills and training management
- Performance evaluations
- Dashboard with charts (gender/department distribution)
- JWT authentication
- Role-based permissions (Admin, HR, Project Manager)
- Soft delete with archive system

## Project Structure

```
├── client/          # Vue.js frontend application
├── server/          # Laravel backend API
└── README.md        # This file
```

## Quick Start

### Option 1: Docker (Recommended)

#### Prerequisites
- Docker
- Docker Compose

#### Installation with Docker
```bash
# Clone the repository
git clone <repository-url>
cd <project-directory>

# Copy environment files
cp .env.docker .env
cp client/.env.docker client/.env

# Start all services
docker-compose up -d

# View logs (optional)
docker-compose logs -f
```

**That's it!** The application will be running at:
- Frontend: `http://localhost:8080`
- Backend API: `http://localhost:8000`
- Database: `localhost:3306`

#### Docker Commands
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Rebuild containers
docker-compose up -d --build

# Access backend container
docker-compose exec backend bash

# Access database
docker-compose exec db mysql -u hr_user -p hr_management
```

### Option 2: Manual Installation

#### Prerequisites
- PHP >= 8.2
- Composer 2.x
- Node.js >= 18 & npm
- MySQL

#### Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd <project-directory>
```

2. **Backend Setup**
```bash
cd server
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
# Configure database in .env
php artisan migrate --seed
php artisan serve
```

3. **Frontend Setup**
```bash
cd client
npm install
# Configure API URL in .env (use VITE_ prefix for environment variables)
npm run dev
```

Backend runs on `http://localhost:8000`
Frontend runs on `http://localhost:8080`

## Migration Status

This project has been migrated from Laravel 7 to Laravel 11 and Vue.js 2 to Vue.js 3. See [MIGRATION_GUIDE.md](./MIGRATION_GUIDE.md) for:
- Complete migration details
- Breaking changes
- Underdeveloped areas and missing features
- Recommended next steps for development

**Note:** Some features are incomplete or use placeholder values. Please refer to the migration guide for a comprehensive list of areas needing development.

## Default Users

After seeding, you can login with:
- **Admin**: `manager@example.com` / `password`
- **HR**: `rh@example.com` / `password`  
- **Project Manager**: `projectmanager@example.com` / `password`

## Screenshots

*Add screenshots here*

## Documentation

- [Frontend Documentation](./client/README.md)
- [Backend API Documentation](./server/README.md)

## License

This project was developed as part of a summer internship program. Please refer to your organization's licensing terms.

## Author

Developed by a dedicated intern during summer internship program, showcasing full-stack development skills with modern web technologies.