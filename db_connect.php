<?php
// Database connection settings
$host = 'localhost'; // Change if your database is hosted elsewhere
$dbname = 'final';
$username = 'root'; // Change as per your database user
$password = ''; // Change as per your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
