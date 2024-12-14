<?php
// Database configuration
// $servername = "localhost"; 
// $username = "root";
// $password = "";
// $dbname = "claybourne";

// Database configuration
$servername = "localhost"; // Assuming MySQL is hosted locally
$username = "u453543362_claybourne";
$password = "516156AS1df@";
$dbname = "u453543362_claybourne";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";
?>
