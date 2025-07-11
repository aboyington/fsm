# FSM - Field Service Management System

## Overview

FSM (Field Service Management) is a comprehensive web-based application designed to streamline field service operations. Built with CodeIgniter 4, it provides tools for managing technicians, work orders, customer relationships, and organizational settings.

## Features

### Current Features
- **Organization Management**
  - Company profile configuration
  - Business location settings
  - Timezone and locale preferences
  - Industry-specific configurations
  - Business hours management (24x7, 24x5, custom)

- **Fiscal Year Settings**
  - Calendar year or custom fiscal year configuration
  - Flexible start/end date settings
  - Year-over-year tracking

- **Currency Management**
  - Multiple currency support
  - Exchange rate configuration
  - Base currency designation
  - Number formatting preferences (thousand/decimal separators)
  - ISO code compliance

- **Authentication System**
  - Session-based authentication for web interface
  - JWT token-based authentication for API
  - Secure login/logout functionality

- **RESTful API**
  - Customer management endpoints
  - Authentication endpoints
  - JSON response format

### Planned Features
- Work order management
- Technician scheduling
- Route optimization
- Customer portal
- Inventory management
- Reporting and analytics

## Technology Stack

- **Backend**: CodeIgniter 4.6.1 (PHP 8.1+)
- **Frontend**: Bootstrap 5, Vanilla JavaScript
- **Database**: SQLite (development), MySQL/PostgreSQL (production ready)
- **Authentication**: Session-based for web, JWT for API

## Requirements

- PHP 8.1 or higher
- Composer
- SQLite (for development) or MySQL/PostgreSQL (for production)
- Extensions:
  - intl
  - mbstring
  - json
  - sqlite3 or mysqlnd
  - curl

## Installation

1. Clone the repository:
```bash
git clone https://github.com/aboyington/fsm.git
cd fsm
```

2. Install dependencies:
```bash
composer install
```

3. Copy and configure environment file:
```bash
cp env .env
# Edit .env file with your settings
```

4. Set up the database:
```bash
php spark migrate
php spark db:seed UserSeeder
```

5. Start the development server:
```bash
php spark serve
```

6. Access the application:
- Web: http://localhost:8080
- Default login: admin@example.com / password123

## Project Structure

```
fsm/
├── app/
│   ├── Controllers/      # Application controllers
│   ├── Models/          # Database models
│   ├── Views/           # View templates
│   ├── Database/        # Migrations and seeders
│   └── Config/          # Configuration files
├── public/              # Public assets
├── writable/            # Logs, cache, uploads
└── docs/                # Documentation
```

## API Documentation

### Authentication
```bash
# Login
POST /api/auth/login
{
  "email": "admin@example.com",
  "password": "password123"
}

# Response
{
  "status": "success",
  "token": "jwt_token_here",
  "user": {...}
}
```

### Customers
```bash
# Get all customers
GET /api/customers
Authorization: Bearer {token}

# Create customer
POST /api/customers
Authorization: Bearer {token}
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "123-456-7890"
}
```

## Development

### Running Tests
```bash
php spark test
```

### Database Migrations
```bash
# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Create new migration
php spark make:migration CreateTableName
```

### Seeding Data
```bash
# Run all seeders
php spark db:seed

# Run specific seeder
php spark db:seed UserSeeder
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the development team.

## Acknowledgments

- Built with [CodeIgniter 4](https://codeigniter.com/)
- UI components from [Bootstrap 5](https://getbootstrap.com/)
- Icons from [Bootstrap Icons](https://icons.getbootstrap.com/)
