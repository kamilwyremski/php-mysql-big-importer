<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Remove execution time and memory limits
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); 
ignore_user_abort(true);   

// ⚙️ CONFIGURATION
$sqlFile = __DIR__ . '/base.sql'; // path to your SQL file
$dsn = "mysql:host=localhost;dbname=DBNAME;charset=utf8"; // replace DBNAME
$user = "DBUSER"; // replace with your DB username
$pass = "PASSWORD"; // replace with your DB password

// 📌 Connect to MySQL using PDO
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_LOCAL_INFILE => true
    ]);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// 📌 Check if SQL file exists
if (!file_exists($sqlFile)) {
    die("SQL file does not exist: $sqlFile");
}

echo "Import started...<br>";

// Disable foreign key checks temporarily
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

// Open file for reading
$handle = fopen($sqlFile, "r");
$query = "";
$lineNumber = 0;
$executed = 0;

// Read file line by line
while (($line = fgets($handle)) !== false) {
    $lineNumber++;

    // Skip empty lines and comments
    if (trim($line) === '' || preg_match('/^(--|#)/', trim($line))) {
        continue;
    }

    // Skip CREATE DATABASE and USE statements
    if (preg_match('/^\s*CREATE DATABASE/i', $line)) {
        continue;
    }
    if (preg_match('/^\s*USE\s+/i', $line)) {
        continue;
    }

    // Append line to current query
    $query .= $line;

    // If line ends with semicolon, execute the query
    if (substr(trim($line), -1) === ';') {
        try {
            $pdo->exec($query);
            $executed++;
        } catch (Exception $e) {
            echo "<br><b>Error on line $lineNumber:</b> " . $e->getMessage() . "<br>";
            echo "<pre>$query</pre>";
            exit;
        }
        $query = "";

        // Display progress every 100 queries
        if ($executed % 100 === 0) {
            echo "Executed $executed queries...<br>";
            ob_flush();
            flush();
        }
    }
}

// Close the file
fclose($handle);

// Re-enable foreign key checks
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

echo "<br>Import finished. Executed $executed queries.";
