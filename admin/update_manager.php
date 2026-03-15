<?php
include '../config/conn.php';
header('Content-Type: application/json');

if (isset($_POST['dorm_manager'])) {
    $manager = $_POST['dorm_manager'];

    $stmt = $conn->prepare("UPDATE system_settings SET setting_value=? WHERE setting_key='dorm_manager'");
    $stmt->bind_param("s", $manager);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No name provided']);
}
