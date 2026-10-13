# Life Network Campus

Life Network Campus is a university-focused social and career platform for goal-driven students. The project uses PHP 8.2+, PDO, MySQL, and a structured MVC-inspired architecture.

## MVP status

The MVP includes authentication, profile and settings, goals with milestones, projects, communities, opportunities, opportunity discovery and applications, portfolio items, security hardening, and responsive layout rules.

## Local setup

1. Put the project under your XAMPP `htdocs` folder.
2. Copy `.env.example` to `.env` and update DB credentials.
3. Import the schema from `database/schema.sql` into MySQL.
4. Start Apache and MySQL in XAMPP.
5. Open `http://localhost/life-network-campus/public` in your browser.

### Demonstration data

After importing the schema, run the local-only seed file:

```sql
SOURCE database/seed.sql;
```

The demo account is:

- Email: `demo.student@life-network-campus.test`
- Password: `password`

Change or remove demo credentials before deploying outside a local environment.

## Main routes

- `/public/register` and `/public/login` for authentication
- `/public/dashboard` for the student overview
- `/public/goals`, `/public/projects`, and `/public/communities` for planning and collaboration
- `/public/opportunities` for owned opportunities
- `/public/opportunities/discover` for saving and applying to open opportunities
- `/public/portfolio` for portfolio items
- `/public/profile` and `/public/settings` for account management

## Project structure

- `app/` contains controllers, repositories, validators, helpers, middleware, and views.
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

## Validation

Run the route and security checks from the project directory:

```text
php tests/security_regression.php
php tests/auth_smoke_public.php
php tests/goals_route_smoke.php
php tests/projects_route_smoke.php
php tests/communities_route_smoke.php
php tests/opportunities_route_smoke.php
php tests/opportunity_discover_route_smoke.php
php tests/portfolio_route_smoke.php
```

The tests validate rendering and shared security behavior. Full CRUD verification requires Apache and MySQL to be running with the schema imported.
