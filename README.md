# Fedi Shelter — animal adoption project

A PHP/MySQL school project for browsing animals, submitting adoption requests and following their status.

## Local setup

1. Install XAMPP/WAMP/Laragon with PHP 8+ and MySQL/MariaDB.
2. In phpMyAdmin, create a database named animalbd, select it, then import animalbd.sql.
3. Import migration.sql once. It enables automatic IDs and prevents duplicate pending requests at database level.
4. Import admin-migration.sql once to add staff/admin roles. Keep this separate so existing installations can upgrade without rerunning the original migration.
5. Check the database credentials in config.php.
6. For local testing, the sample login is username sami23 and password pass123. These are demonstration credentials only; replace the sample users before deployment.
7. Place the project in the web root and open http://localhost/animal-shelter/.

## Request tracking

- After a successful adoption request, the requester is signed in and can open my_requests.php.
- Returning users can sign in on my_requests.php to see all their submitted requests and each status: waiting, accepted or rejected.
- The page uses prepared statements, password verification and session regeneration.

## Staff administration

- Promote a trusted existing account to administrator in phpMyAdmin with:
  UPDATE utilisateur SET role = 'admin' WHERE username = 'YOUR_USERNAME';
- Open admin.php, sign in with that account, then accept or reject pending adoption requests.
- The administration routes require an admin role, use prepared statements, and protect state-changing actions with CSRF tokens.

## Features

- Filter animals by type and optional maximum age.
- Display animal records from MySQL.
- Submit an adoption request for an existing animal using an existing user account.
- Prevent duplicate pending requests for the same user and animal, including concurrent submissions.
- Follow the status of personal requests.
- Review and accept/reject pending adoption requests through a staff-only dashboard.
- Verify the PGCD challenge and CSRF token.
- Use prepared statements and escape database values before HTML output.

Legacy plaintext sample passwords are accepted once for compatibility and immediately replaced with password_hash() hashes after successful login. For a public deployment, replace demo accounts and add a password-change/registration workflow.
