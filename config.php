<?php
// Database connection settings
$servername = "localhost";  // Replace if your database server is different
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "dashboard";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Optional: Set timezone if needed
// date_default_timezone_set('America/New_York');

// Optional: Set error handling
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>