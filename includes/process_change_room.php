<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? 0;
    $new_room = $_POST['new_room'] ?? '';

    if (!$student_id) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
        exit;
    }

    if (!$new_room) {
        echo json_encode(['status' => 'error', 'message' => 'Please select a room.']);
        exit;
    }

    // Check if room is available
    $check_room = $conn->prepare("SELECT capacity, occupied FROM rooms WHERE room_number = ? LIMIT 1");
    $check_room->bind_param("s", $new_room);
    $check_room->execute();
    $result = $check_room->get_result();
    $room = $result->fetch_assoc();

    if (!$room || $room['occupied'] >= $room['capacity']) {
        echo json_encode(['status' => 'error', 'message' => 'Selected room is full.']);
        exit;
    }

    // Insert request
    $stmt = $conn->prepare("INSERT INTO room_change_requests (student_id, requested_room, status, requested_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("ss", $student_id, $new_room);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Room change request submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
