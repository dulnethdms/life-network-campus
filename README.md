# Life Network Campus

Life Network Campus is a university-focused social and career platform for goal-driven students. The project uses PHP 8.2+, PDO, MySQL, and a structured MVC-inspired architecture.

## Phase 2 status

This repository currently contains the project foundation, environment configuration, and database schema for the MVP.

## Local setup

1. Put the project under your XAMPP `htdocs` folder.
2. Copy `.env.example` to `.env` and update DB credentials.
3. Import the schema from `database/schema.sql` into MySQL.
4. Start Apache and MySQL in XAMPP.
5. Open `http://localhost/life-network-campus/public` in your browser.

## Project structure

- `app/` contains controllers, models, services, validators, and helpers.
- `config/` contains runtime configuration.
- `database/` contains schema and seed SQL files.
- `public/` is the document root for Apache.
- `routes/` contains web and API routing definitions.
- `storage/logs/` is reserved for logs.

## Database setup

```sql
CREATE DATABASE IF NOT EXISTS life_network_campus;
USE life_network_campus;
SOURCE database/schema.sql;
```

## Notes

- Seed data is intentionally separate from production data.
- The project is designed for local development in XAMPP.
- Sensitive values belong in `.env` and must not be committed.
