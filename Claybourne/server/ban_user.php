<?php
require '../database/db_connect.php'; // Include your database connection script
header('Content-Type: application/json'); // Ensure the response is treated as JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];

    // Check if the user is already in the ip_blocker table
    $checkSql = "SELECT * FROM ip_blocker WHERE email_address = (SELECT email FROM winners WHERE id = ?)";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // User already banned, update total_attempts and time_blocked
        $updateIpBlockerSql = "UPDATE ip_blocker SET total_attempts = total_attempts + 1, time_blocked = NOW() WHERE email_address = (SELECT email FROM winners WHERE id = ?)";
        $updateIpBlockerStmt = $conn->prepare($updateIpBlockerSql);
        $updateIpBlockerStmt->bind_param("i", $userId);

        if ($updateIpBlockerStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $updateIpBlockerStmt->error]);
        }

        $updateIpBlockerStmt->close();
    } else {
        // User not yet banned, insert into ip_blocker
        $insertSql = "INSERT INTO ip_blocker (ip_address, access_time, first_name, last_name, email_address, time_blocked, total_attempts, banned)
                      SELECT ip_address, NOW(), first_name, last_name, email, NOW(), 1, 1
                      FROM winners WHERE id = ?";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("i", $userId);

        if ($insertStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $insertStmt->error]);
        }

        $insertStmt->close();
    }

    // Update the winners table to set banned = true, suspicion_level = 'High', and approval_status = 'Denied'
    $updateWinnersSql = "UPDATE winners SET banned = 1, suspicion_level = 'High', approval_status = 'Denied' WHERE id = ?";
    $updateWinnersStmt = $conn->prepare($updateWinnersSql);
    $updateWinnersStmt->bind_param("i", $userId);

    // Execute the update and provide feedback based on the outcome
    if ($updateWinnersStmt->execute()) {
        // echo json_encode(['success' => true, 'message' => 'User banned, marked as High suspicion level, and approval status set to Denied.']);
    } else {
        // echo json_encode(['success' => false, 'error' => 'Failed to update user status, suspicion level, and approval status. Error: ' . $updateWinnersStmt->error]);
    }

    // Close the statement after execution
    $updateWinnersStmt->close();

    // Close any other prepared statements and the database connection
    $checkStmt->close();
    $conn->close();
}
?>
