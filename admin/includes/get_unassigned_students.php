<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "idorm_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$room_id = $_GET['room_id'] ?? 0;
if (!$room_id) exit('Invalid room');

// Fetch students not yet assigned to any room
$query = "
    SELECT u.student_id, p.full_name, e.year_level, e.course
    FROM users u
    INNER JOIN user_personal_information p ON u.student_id = p.student_id
    INNER JOIN user_educational_background e ON u.student_id = e.student_id
    LEFT JOIN room_assignments r ON u.student_id = r.student_id
    WHERE u.role='student' AND r.student_id IS NULL
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) < 1) {
    echo "<tr><td colspan='5' class='text-center'>No unassigned students found.</td></tr>";
} else {
    while ($student = mysqli_fetch_assoc($result)) {
        echo "<tr>
        <td><input type='checkbox' name='students[]' value='{$student['student_id']}'></td>
        <td>{$student['student_id']}</td>
        <td>{$student['full_name']}</td>
        <td>{$student['year_level']}</td>
        <td>{$student['course']}</td>
    </tr>";
    }
}
