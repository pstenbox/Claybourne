<?php
require '../database/db_connect.php';

// Define the log directory and check if it exists, if not create it
$logDir = '../logs/Pin Errors';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/log.txt';

$pinCode = $_POST['pinCode'] ?? '';

// Log the received pin code
file_put_contents($logFile, "Received pinCode: " . $pinCode . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

if (empty($pinCode)) {
    echo json_encode(['isValid' => false, 'error' => 'No PIN code provided.']);
    file_put_contents($logFile, "Error: No PIN code provided at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    exit;
}

$sql = "SELECT id, total_uses, max_uses, date_redeemed FROM pin_codes WHERE pin_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $pinCode);
$stmt->execute();
$result = $stmt->get_result();

// Log the number of rows found
file_put_contents($logFile, "Rows found: " . $result->num_rows . " for PIN code: " . $pinCode . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['total_uses'] < $row['max_uses']) {
        // Increment total_uses and update date_redeemed because the pin code is valid and not yet maxed out
        $new_total_uses = $row['total_uses'] + 1;
        $updateSql = "UPDATE pin_codes SET total_uses = ?, date_redeemed = NOW() WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $new_total_uses, $row['id']);
        $updateStmt->execute();

        // Check for SQL errors
        if ($updateStmt->error) {
            file_put_contents($logFile, "SQL Error: " . $updateStmt->error . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
            echo json_encode(['isValid' => false, 'error' => 'SQL Error on update.']);
        } else {
            echo json_encode(['isValid' => true]); // Always return valid if no SQL error
        }
        $updateStmt->close();
    } else {
        echo json_encode(['isValid' => false, 'error' => 'This pin code has already been redeemed.']);
        file_put_contents($logFile, "Error: PIN usage limit reached for PIN code: " . $pinCode . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    }
} else {
    echo json_encode(['isValid' => false, 'error' => 'Invalid PIN code.']);
    file_put_contents($logFile, "Error: Invalid PIN code entered: " . $pinCode . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
}

$conn->close();
?>
