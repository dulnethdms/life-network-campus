# Life Network Campus Demo Guide

## Start the application

1. Start Apache and MySQL in XAMPP.
2. Import `database/schema.sql` into MySQL.
3. Import `database/seed.sql` for demonstration records.
4. Open `http://localhost/life-network-campus/public`.

## Demo account

- Email: `demo.student@life-network-campus.test`
- Password: `password`

## Suggested demonstration flow

1. Log in and show the dashboard summary.
2. Open the profile page and show university, field, skills, and interests.
3. Create a goal and add a milestone, then mark the milestone complete.
4. Create a project and a community from their management pages.
5. Open opportunity discovery, save an opportunity, and submit an application message.
6. Add a portfolio item and switch its visibility between public and private.
7. Return to the dashboard to show the platform as a single student growth workspace.

## Reset local demo data

For a clean demo environment, recreate the `life_network_campus` database, import `database/schema.sql`, and then import `database/seed.sql` again. The seed account and records are for local demonstration only.