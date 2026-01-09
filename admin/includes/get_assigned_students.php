<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "idorm_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the room number from AJAX
$room_number = $_GET['room_number'] ?? '';
if (!$room_number) {
    echo "<tr><td colspan='5' class='text-center'>Invalid room.</td></tr>";
    exit;
}

// Fetch students assigned to this room
$query = "
    SELECT u.student_id, p.full_name, p.contact, e.year_level, e.course
    FROM room_assignments r
    INNER JOIN users u ON r.student_id = u.student_id
    INNER JOIN user_personal_information p ON u.student_id = p.student_id
    INNER JOIN user_educational_background e ON u.student_id = e.student_id
    INNER JOIN rooms rm ON r.room_num = rm.room_number
    WHERE rm.room_number = '$room_number'
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) < 1) {
    echo "<tr><td colspan='5' class='text-center'>No students assigned to this room.</td></tr>";
} else {
    while ($student = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$student['student_id']}</td>
            <td>{$student['full_name']}</td>
            <td>{$student['contact']}</td>
            <td><span class='badge bg-success'>Active</span></td>
            <td>
                <button class='btn btn-outline-danger btn-sm remove-assignment' data-student='{$student['student_id']}' data-room='{$room_number}'>
                    <i class='bi bi-person-dash'></i>
                </button>
            </td>
        </tr>";
    }
}
