<?php
require '../database/db_connect.php';
header('Content-Type: application/json'); // Ensure the response is treated as JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $suspicionLevel = $_POST['suspicionLevel'];

    $sql = "UPDATE winners SET suspicion_level = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $suspicionLevel, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
