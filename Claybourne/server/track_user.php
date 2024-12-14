<?php
require '../database/db_connect.php';  // Ensure the path to your database connection script is correct

header('Content-Type: application/json');

// Retrieve data from POST request
$deviceInfo = $_POST['device_info'] ?? 'Unknown';
$screenResolution = $_POST['screen_resolution'] ?? 'Unknown';
$operatingSystem = $_POST['operating_system'] ?? 'Unknown';
$browserLanguage = $_POST['browser_language'] ?? 'Unknown';
$deviceType = $_POST['device_type'] ?? 'Unknown';
$hasVisitedBefore = isset($_POST['has_visited_before']) ? (bool)$_POST['has_visited_before'] : false;
$hasBeenFlagged = isset($_POST['has_been_flagged']) ? (bool)$_POST['has_been_flagged'] : false;

// IP address retrieved server-side for accuracy
$ipAddress = $_SERVER['REMOTE_ADDR'];

// Check if the IP address exists in the ip_blocker table
$checkSql = "SELECT id, total_attempts FROM ip_blocker WHERE ip_address = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $ipAddress);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // User is in ip_blocker, update total_attempts and access_time
    $row = $checkResult->fetch_assoc();
    $totalAttempts = $row['total_attempts'] + 1;
    $updateSql = "UPDATE ip_blocker SET total_attempts = ?, access_time = NOW() WHERE ip_address = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("is", $totalAttempts, $ipAddress);
    $updateStmt->execute();
    $updateStmt->close();
}

// Insert user tracking data regardless of IP blocking status
$insertSql = "INSERT INTO user_tracking (ip_address, device_info, screen_resolution, operating_system, browser_language, device_type, has_visited_before, has_been_flagged, access_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($insertSql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'error' => 'Failed to prepare the database statement.']);
    exit;
}

$stmt->bind_param("ssssssii", $ipAddress, $deviceInfo, $screenResolution, $operatingSystem, $browserLanguage, $deviceType, $hasVisitedBefore, $hasBeenFlagged);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true, 'message' => 'User tracking data successfully recorded.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to insert user tracking data: ' . $stmt->error]);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
