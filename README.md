# Fedi Shelter — animal adoption project

A small PHP/MySQL school project for browsing animals and submitting adoption requests.

## Local setup

1. Install XAMPP/WAMP/Laragon with PHP 8+ and MySQL/MariaDB.
2. In phpMyAdmin, create a database named `animalbd`, select it, then import `animalbd.sql`.
3. Import `migration.sql` once. It enables automatic IDs and adds a database-level safeguard against duplicate pending requests for the same user and animal.
4. Check the database credentials in `config.php`.
5. For local testing, the sample login is username `sami23` and password `pass123`. These are demonstration credentials only; replace the sample users before deployment.
6. Place the project in the web root and open `http://localhost/animal-shelter/`.

## Features

- Filter animals by type and optional maximum age.
- Display real animal records from MySQL.
- Submit an adoption request for an existing animal using an existing user account.
- Prevent duplicate pending requests for the same user and animal.
- Verify a simple PGCD challenge and CSRF token.
- Use prepared statements and escape database values before HTML output.

Legacy plaintext sample passwords are accepted once for compatibility and immediately replaced with `password_hash()` hashes after a successful login. For a public deployment, replace demo accounts and add a proper password-change/registration workflow.
