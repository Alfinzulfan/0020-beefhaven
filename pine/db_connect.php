<?php
$servername = "localhost";  // Change if necessary
$username = "root";  // Change to your database username
$password = "";  // Change to your database password
$dbname = "beef_heaven";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>