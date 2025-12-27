# PHP MySQL Big SQL Importer

A simple PHP script to import **very large `.sql` files** into MySQL without time or memory limits.  
Perfect for cases where phpMyAdmin fails to load large database dumps.

---

## 🚀 Features

- Handles large SQL files
- Removes PHP execution time and memory limits
- Reads SQL file line by line (low memory usage)
- Supports multi-line SQL queries
- Ignores:
  - SQL comments
  - `CREATE DATABASE` statements
  - `USE dbname` statements
- Temporarily disables `FOREIGN_KEY_CHECKS`
- Displays import progress in browser
- Clear error reporting (line number + full query)

---

## 📦 Requirements

- PHP 7.2+ (PHP 8.x recommended)
- MySQL / MariaDB
- File access on server (CLI or web)
- PDO MySQL extension enabled

## ⚙️ Installation

1. Place your `.sql` file in the same directory as the script (e.g., `baza.sql`).

2. Edit database configuration in `import.php`:

```php
$sqlFile = __DIR__ . '/baza.sql';
$dsn = "mysql:host=localhost;dbname=DBNAME;charset=utf8";
$user = "DBUSER";
$pass = "PASSWORD";
```

## ▶️ Usage
Option 1: via Web Browser
Open import.php in your browser:
```
https://yourdomain.com/import.php
```
Option 2: via CLI
```
php import.php
```

## 🛡️ Security
⚠️ Do not run this script on a public server without protection.

Recommendations:

Delete the script after import

Protect the folder with HTTP password

Do not commit sensitive credentials

Do not expose .sql file publicly

## 📄 License
MIT License – use, modify, and integrate freely.

## ✍️ Author
Kamil Wyremski
https://wyremski.pl/en

