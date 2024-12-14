<?php
require '../database/db_connect.php';
header('Content-Type: application/json'); // Ensure the response is treated as JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autosend = $_POST['autosend']; // Assuming the autosend value is received as 'true' or 'false'

    // Properly escape the value to prevent SQL injection
    $autosend_escaped = $conn->real_escape_string($autosend);

    // Update the autosend value in the autoemail table
    $sql = "UPDATE autoemail SET autosend = '$autosend_escaped' WHERE id = 1"; // Assuming id is always 1
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Autosend value updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error updating autosend value: ' . $conn->error]);
    }
}

$conn->close();
?>
