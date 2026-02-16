# AZTravel Travel Booking Platform

## Overview
AZTravel is a PHP + MySQL travel booking showcase with three trip categories, a secure admin dashboard, and animated UI.

## Setup
1. Ensure Apache and MySQL are running (XAMPP).
2. Create the database and tables:
   - Import `sql/aztravel.sql` into MySQL (phpMyAdmin or CLI).
3. Update database credentials in `includes/config.php` if needed.
4. Visit `http://localhost/aztravel/index.php`.

## Admin Login
- Username: `admin`
- Password: `admin123`

## Pages
- Home: `index.php`
- Religious trips: `religious.php`
- Domestic trips: `domestic.php`
- International trips: `international.php`
- Admin login: `login.php`
- Dashboard: `dashboard.php`

## Notes
- Images are loaded from remote URLs (Unsplash).
- Contact form is a safe front-end submission placeholder (no email sent).
