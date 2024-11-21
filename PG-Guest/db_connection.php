<?php
$servername = "localhost"; // Database server (usually localhost)
$username = "root"; // Database username (use your actual database username)
$password = "12345"; // Database password (use your actual password)
$dbname = "staymate"; // Your database name (replace with actual database name)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// For better security, consider using PDO with prepared statements for sensitive operations like signup/login.
?>
