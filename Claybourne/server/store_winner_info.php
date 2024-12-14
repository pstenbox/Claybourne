<?php
require '../database/db_connect.php'; // Include your database connection script
header('Content-Type: application/json');

/* Store Name */
$globalStoreName = $_POST['globalStoreName'] ?? '';

// Retrieve data from POST request
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$giftCardCode = $_POST['giftCardCode'] ?? '';
$suspicionLevel = $_POST['suspicionLevel'] ?? '';
$approvalStatus = $_POST['approvalStatus'] ?? '';
$shirtSize = $_POST['shirtSize'] ?? ''; // New shirt size field
$skillAnswer = $_POST['skillAnswer'] ?? ''; // New skill testing answer field

// Validate incoming data (add additional validation if required)
if (empty($firstName) || empty($lastName) || empty($email) || empty($giftCardCode) || empty($globalStoreName) || empty($shirtSize) || empty($skillAnswer)) {
    echo json_encode(['isValid' => false, 'error' => 'Incomplete information provided.']);
    exit;
}

// Prepare and execute SQL query to insert winner information
$insertSql = "INSERT INTO winners (first_name, last_name, email, ip_address, time_won, gift_card_code, shirt_size, skill_testing_answer, suspicion_level, approval_status) 
              VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("sssssssss", $firstName, $lastName, $email, $_SERVER['REMOTE_ADDR'], $giftCardCode, $shirtSize, $skillAnswer, $suspicionLevel, $approvalStatus);
if ($insertStmt->execute() && $insertStmt->affected_rows > 0) {
    // Update the store_activity_logs if the insert was successful
    $updateStoreSql = "UPDATE store_activity_logs SET items_ordered = items_ordered + 1 WHERE store_name = ?";
    $updateStoreStmt = $conn->prepare($updateStoreSql);
    $updateStoreStmt->bind_param("s", $globalStoreName);
    if ($updateStoreStmt->execute()) {
        echo json_encode(['isValid' => true, 'message' => 'Winner information inserted and store activity updated.']);
    } else {
        echo json_encode(['isValid' => false, 'error' => 'Failed to update store activity. SQL Error: ' . $updateStoreStmt->error]);
    }
    $updateStoreStmt->close();
} else {
    echo json_encode(['isValid' => false, 'error' => 'Failed to insert winner information. SQL Error: ' . $insertStmt->error]);
}

$insertStmt->close();
$conn->close();
?>
