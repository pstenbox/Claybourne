<?php
require '../database/db_connect.php'; // Include your database connection script

header('Content-Type: application/json');

// Retrieve data from POST request
$userId = $_POST['userId'] ?? '';

// Validate incoming data (add additional validation if required)
if (empty($userId)) {
    echo json_encode(['success' => false, 'error' => 'User ID is missing.']);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Prepare and execute SQL query to update approval status to "Code Sent"
    $updateSql = "UPDATE winners SET approval_status = 'Code Sent' WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $userId);
    $updateStmt->execute();

    // Check if update was successful
    if ($updateStmt->affected_rows > 0) {
        // Prepare and execute query to fetch user's email
        $emailSql = "SELECT email FROM winners WHERE id = ?";
        $emailStmt = $conn->prepare($emailSql);
        $emailStmt->bind_param("i", $userId);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();

        if ($email = $emailResult->fetch_assoc()) {
            // Commit the transaction
            $conn->commit();

            echo json_encode(['success' => true, 'email' => $email['email']]);
        } else {
            throw new Exception('Email not found.');
        }
    } else {
        throw new Exception('Failed to update approval status.');
    }
} catch (Exception $e) {
    // If an exception occurred, roll back the transaction and return an error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
