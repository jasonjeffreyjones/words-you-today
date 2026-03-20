# Words You Today

Words You Today is a low-friction self-reflection app built with plain PHP and MySQL for simple shared-hosting deployment.

Instead of asking users to write conventional journal entries, the app presents one identity signifier at a time and asks a single question:

> Does this describe you today?

Users answer with a swipe or a button tap. Over time, those responses create a personal record of how they describe themselves from day to day.

## Status

Version 1.1 adds account-management and data-export improvements on top of the core flow:

- account creation and login
- indefinite login until intentional logout
- change email and password
- forgot/reset password flow
- one-signifier-at-a-time response flow
- yes/no recording by user and day
- basic personal stats
- personal CSV data export

## Stack

- PHP
- MySQL
- Bootstrap 5
- minimal JavaScript for swipe interaction

The code is intentionally simple, readable, and deployable on ordinary shared hosting without a framework or build step.

## Core Idea

Words You Today is journal-like in its long-term personal value, but it removes the friction of writing. The product centers on repeated lightweight self-description rather than freeform text entry.

## Project Structure

- `index.php`: home page
- `wyt.php`: main response screen
- `answer.php`: JSON endpoint for saving a response and returning the next signifier
- `signup.php`, `login.php`, `logout.php`, `account.php`, `stats.php`: user/account pages
- `forgot-password.php`, `reset-password.php`, `download-my-data.php`: account recovery and data export
- `includes/`: configuration, database, auth, and helper logic
- `templates/`: shared page templates
- `sql/schema.sql`: full schema for fresh installs
- `sql/migrations/`: incremental production-safe schema changes
- `public-assets/`: minimal CSS

## Shared Hosting Setup

1. Create a MySQL database and user.
2. Import `sql/schema.sql`.
3. Create a non-public config file outside the web root, such as `/home/youraccount/wyt-config.php`.
4. Use `wyt-config.example.php` as the template for that file.
5. Upload the repository contents to your app directory, such as `public_html/words-you-today/`.
6. Make sure your host serves `index.php`.

Example external config:

```php
<?php

declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'words_you_today');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_real_password');
define('WYT_EXPORT_DIR', '/home/youraccount/wyt-exports');
```

## Deployment

This repo includes `deploy.example.sh` as a simple `rsync`-based deployment template. Copy it to `deploy.sh`, fill in your real values locally, and keep `deploy.sh` out of Git.

## User Value Focus

The project is guided by its focus on providing value to the end user:

- no ads
- no sale of personal data
- open-source system
- eventual support for anonymized research data

The intention is to provide enough value to the user, that they are happy to share their anonymized data with social science researchers.

See `founding-statement.php` for the project’s founding statement.

Developed by [Dr. Jason Jeffrey Jones](https://jasonjones.ninja)

Use the app at [https://jasonjones.ninja/words-you-today](https://jasonjones.ninja/words-you-today)
