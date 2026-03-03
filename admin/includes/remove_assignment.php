<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "idorm_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$student_id  = $_GET['student_id'] ?? '';
$room_number = $_GET['room_number'] ?? '';

if (!$student_id || !$room_number) {
    echo "Invalid request";
    exit;
}

// Remove student assignment from the room
// Delete assignment
$sql = mysqli_query($conn, "
    DELETE FROM room_assignments
    WHERE student_id = '$student_id'
    AND room_num = '$room_number'
");

// Recalculate occupied count
$sql2 = mysqli_query($conn, "
    UPDATE rooms r
    SET r.occupied = (
        SELECT COUNT(*) 
        FROM room_assignments ra 
        WHERE ra.room_num = r.room_number
    )
    WHERE r.room_number = '$room_number'
");

if ($sql && $sql2) {
    echo "Student removed successfully";
} else {
    echo "Failed to remove student";
}
