<?php
require '../database/db_connect.php';  // Database connection

header('Content-Type: application/json');  // Set response type to JSON

// Query to select the autosend status
$sql = "SELECT autosend FROM autoemail WHERE id = 1";  // Adjust if necessary
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $autosend = $row['autosend'] === 'true';  // Ensuring only 'true' is treated as true
    echo json_encode(['autosend' => $autosend]);
} else {
    echo json_encode(['autosend' => false]);  // Default to false if no record found
}

$conn->close();
?>
