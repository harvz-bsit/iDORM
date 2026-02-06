<?php
include 'config/conn.php'; // your database connection

// Admin credentials
$student_id = '00000';
$email = 'admin@idorm.ispsc.edu';
$password_plain = 'admin123';
$role = 'admin';

// Hash the password
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Check if admin already exists
$checkQuery = "SELECT * FROM users WHERE student_id = '$student_id' LIMIT 1";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    echo "Admin user already exists!";
    exit;
}

// Insert admin into the database
$insertQuery = "INSERT INTO users (student_id, email, password, role)
                VALUES ('$student_id', '$email', '$password_hashed', '$role')";

if (mysqli_query($conn, $insertQuery)) {
    header("Location: login.php?admin_created=1");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
