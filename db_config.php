<?php
// db_config.php - Database configuration file

// Define database credentials as constants
define('DB_SERVER', 'localhost'); // Database host (localhost or IP address)
define('DB_USERNAME', 'root'); // Database username
define('DB_PASSWORD', ''); // Database password (leave empty if there's none)
define('DB_NAME', 'ad_bidding_system'); // Name of your database

// Create a connection using MySQLi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
