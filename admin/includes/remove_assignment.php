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
$query = "
    DELETE FROM room_assignments
    WHERE student_id = '$student_id'
    AND room_num = '$room_number'
";

if (mysqli_query($conn, $query)) {
    echo "Student removed successfully";
} else {
    echo "Failed to remove student";
}
