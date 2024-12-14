<?php
require '../../database/db_connect.php';

header('Content-Type: application/json');  // Set header to application/json

$sql = "SELECT * FROM winners WHERE banned = 0 AND suspicion_level != 'Medium' AND suspicion_level != 'High'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
    // Remove error debugging in production
    // echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

