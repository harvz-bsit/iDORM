<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $destination = $_POST['destination'];
    $purpose = $_POST['purpose'];
    $return_date = $_POST['return_date'];
    $return_time = $_POST['return_time'];

    // Simple validation
    if (!$departure_date || !$departure_time || !$destination || !$purpose || !$return_date || !$return_time) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO passlips (student_id, departure_date, departure_time, destination, purpose, return_date, return_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $student_id, $departure_date, $departure_time, $destination, $purpose, $return_date, $return_time);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Passlip request submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
