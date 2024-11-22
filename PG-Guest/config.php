<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "staymate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com'); // Replace with your SMTP host
define('SMTP_USER', 'jagadeswararaovana@gmail.com'); // Replace with your email
define('SMTP_PASS', 'vienyxievujtsiit'); // Replace with your email password
define('SMTP_PORT', 587);
?>
