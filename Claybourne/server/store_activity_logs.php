<?php
require '../database/db_connect.php';
header('Content-Type: application/json');

$logDir = '../logs/Submission Logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/submission_log.txt';

$storeName = $_POST['storeName'] ?? '';
$storeAddress = $_POST['storeAddress'] ?? '';
$pinCode = $_POST['pinCode'] ?? '';
$province = $_POST['province'] ?? '';
$storeOwnerName = $_POST['storeOwnerName'] ?? '';

if (empty($storeName) || empty($storeAddress) || empty($pinCode) || empty($province)) {
    echo json_encode(['isValid' => false, 'error' => 'All fields are required.']);
    file_put_contents($logFile, "Missing field data at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    exit;
}

// Start transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Insert or update store information
    $storeSql = "INSERT INTO stores (store_name, address, owner, province, pin_code) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE address = VALUES(address), province = VALUES(province)";
    $storeStmt = $conn->prepare($storeSql);
    $storeStmt->bind_param("sssss", $storeName, $storeAddress, $storeOwnerName, $province, $pinCode);
    $storeStmt->execute();
    $store_id = $storeStmt->insert_id ?: $conn->query("SELECT id FROM stores WHERE store_name = '$storeName'")->fetch_assoc()['id'];

    file_put_contents($logFile, "Store operation, Store ID: " . $store_id . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

    // Check if an activity log for the store name already exists today
    $findLogSql = "SELECT id FROM store_activity_logs WHERE store_name = ? AND DATE(log_date) = CURDATE()";
    $findLogStmt = $conn->prepare($findLogSql);
    $findLogStmt->bind_param("s", $storeName);
    $findLogStmt->execute();
    $findLogResult = $findLogStmt->get_result();

    if ($findLogResult->num_rows > 0) {
        $logId = $findLogResult->fetch_assoc()['id'];
        $updateLogSql = "UPDATE store_activity_logs SET new_pins_logged = new_pins_logged + 1, log_date = NOW() WHERE id = ?";
        $updateLogStmt = $conn->prepare($updateLogSql);
        $updateLogStmt->bind_param("i", $logId);
        $updateLogStmt->execute();
    } else {
        $insertLogSql = "INSERT INTO store_activity_logs (store_id, store_name, new_pins_logged, log_date) VALUES (?, ?, 1, NOW())";
        $insertLogStmt = $conn->prepare($insertLogSql);
        $insertLogStmt->bind_param("is", $store_id, $storeName);
        $insertLogStmt->execute();
    }

    $conn->commit();
    echo json_encode(['isValid' => true]);
} catch (Exception $e) {
    $conn->rollback();
    file_put_contents($logFile, "Transaction failed: " . $e->getMessage() . " at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    echo json_encode(['isValid' => false, 'error' => 'Transaction failed: ' . $e->getMessage()]);
}

$conn->close();
?>
