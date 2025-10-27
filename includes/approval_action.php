<?php
include '../config/conn.php';


if (isset($_POST['action']) && isset($_POST['student_id'])) {
    $action = $_POST['action'];
    $id = intval($_POST['student_id']);

    if ($action === 'approve') {
        $status = 'Approved';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    $query = $conn->prepare("UPDATE application_approvals SET status = ? WHERE student_id = ?");
    $query->bind_param("si", $status, $id);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Applicant has been {$status}."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }
}
