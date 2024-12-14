<?php
require '../database/db_connect.php';  // Include the database connection file

header('Content-Type: application/json');  // Set the correct header for JSON response

// Prepare log file for error tracking
$logDir = '../logs/User Submission Logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/submission_log.txt';

// Collect POST data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$globalPin = $_POST['globalPin'] ?? '';
$shirtSize = $_POST['shirtSize'] ?? ''; // New shirt size field
$skillAnswer = $_POST['skillAnswer'] ?? ''; // New skill testing answer field

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($email) || empty($globalPin) || empty($shirtSize) || empty($skillAnswer)) {
    $error = 'All fields are required.';
    echo json_encode(['isValid' => false, 'error' => $error]);
    file_put_contents($logFile, "$error at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    exit;
}

// Validate skill testing answer (expected: 420)
if ((int)$skillAnswer !== 420) {
    $error = 'Incorrect answer to the skill testing question.';
    echo json_encode(['isValid' => false, 'error' => $error]);
    file_put_contents($logFile, "$error at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    exit;
}

// Check if the PIN has been used by this user
$pinCheckSql = "SELECT id FROM winners WHERE (first_name = ? AND last_name = ? OR email = ?) AND gift_card_code = ?";
$pinCheckStmt = $conn->prepare($pinCheckSql);
$pinCheckStmt->bind_param("ssss", $firstName, $lastName, $email, $globalPin);
$pinCheckStmt->execute();
$pinCheckResult = $pinCheckStmt->get_result();

if ($pinCheckResult->num_rows > 0) {
    // If PIN exists, perform additional duplicate checks
    performDuplicateChecks($conn, $firstName, $lastName, $email);
} else {
    // If PIN is new, skip duplicate checks and insert the data
    insertUserData($conn, $firstName, $lastName, $email, $globalPin, $shirtSize, $skillAnswer);
}

$conn->close();

function performDuplicateChecks($conn, $firstName, $lastName, $email) {
    global $logFile;
    // Check for duplicate first name & last name pair
    $checkNameSql = "SELECT id FROM winners WHERE first_name = ? AND last_name = ?";
    $checkNameStmt = $conn->prepare($checkNameSql);
    $checkNameStmt->bind_param("ss", $firstName, $lastName);
    $checkNameStmt->execute();
    $checkNameResult = $checkNameStmt->get_result();
    if ($checkNameResult->num_rows > 0) {
        $error = 'Sorry, you have already participated in this event.';
        echo json_encode(['isValid' => false, 'error' => $error]);
        file_put_contents($logFile, "$error at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
        exit;
    }

    // Check for duplicate email address
    $checkEmailSql = "SELECT id FROM winners WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();
    if ($checkEmailResult->num_rows > 0) {
        $error = 'Sorry, you have already participated in this event.';
        echo json_encode(['isValid' => false, 'error' => $error]);
        file_put_contents($logFile, "$error at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
        exit;
    }

    insertUserData($conn, $firstName, $lastName, $email, "");
}

function insertUserData($conn, $firstName, $lastName, $email, $pinCode, $shirtSize, $skillAnswer) {
    global $logFile;
    // Insert user data into the database, including shirt size and skill answer
    $insertSql = "INSERT INTO winners (first_name, last_name, email, gift_card_code, shirt_size, skill_testing_answer) VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("sssssi", $firstName, $lastName, $email, $pinCode, $shirtSize, $skillAnswer);
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        echo json_encode(['isValid' => true]);
    } else {
        $error = 'Failed to insert user data.';
        echo json_encode(['isValid' => false, 'error' => $error]);
        file_put_contents($logFile, "$error at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    }
}
?>
