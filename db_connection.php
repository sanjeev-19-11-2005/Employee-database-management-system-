<?php
$host = "localhost"; // Change if your MySQL host is not localhost
$user = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "employee_portal"; // Database name

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
