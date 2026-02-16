<?php
require '../vendor/autoload.php';
include '../config/conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Dompdf\Dompdf;


// Login Using Student ID and Password (Hashed)
if (isset($_POST['login'])) {

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE student_id='$student_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['role'] == 'student') {
            if (password_verify($password, $row['password'])) {
                // Successful login
                session_start();
                $_SESSION['student_id'] = $row['student_id'];
                header("Location: ../student/dashboard.php");
                exit();
            } else {
                // Incorrect password
                header("Location: ../login.php?error=Incorrect Student ID or Password");
                exit();
            }
        } else {
            if (password_verify($password, $row['password'])) {
                // Successful login for admin or staff
                session_start();
                $_SESSION['admin'] = $row['student_id'];
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                // Incorrect password
                header("Location: ../login.php?error=Incorrect Student ID or Password");
                exit();
            }
        }
    } else {
        // Student ID not found
        header("Location: ../login.php?error=Incorrect Student ID or Password");
        exit();
    }
}

// Dormitory Application Submission & User Creation

if (isset($_POST['submit'])) {
    // DB connection assumed as $conn

    // Helper: sanitize input
    function validate($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // -------------------------------
    // 1️⃣ Gather Inputs
    // -------------------------------
    // Personal Information
    $student_id = validate($_POST['student_id']);
    $full_name = validate($_POST['full_name']);
    $nickname = validate($_POST['nickname']);
    $age = validate($_POST['age']);
    $sex = validate($_POST['sex']);
    $civil_status = validate($_POST['civil_status']);
    $nationality = validate($_POST['nationality']);
    $contact = validate($_POST['contact']);
    $address = validate($_POST['address']);
    $date_of_birth = validate($_POST['date_of_birth']);
    $place_of_birth = validate($_POST['place_of_birth']);
    $email = validate($_POST['email']);

    // Educational Background
    $elem_school = validate($_POST['elem_school']);
    $elem_year = validate($_POST['elem_year']);
    $sec_school = validate($_POST['sec_school']);
    $sec_year = validate($_POST['sec_year']);
    $college_school = validate($_POST['college_school']);
    $college_year = validate($_POST['college_year']);
    $course = validate($_POST['course']);
    $year_level = validate($_POST['year_level']);

    // Family Background
    $father_name = validate($_POST['father_name']);
    $father_occupation = validate($_POST['father_occupation']);
    $father_age = validate($_POST['father_age']);
    $father_contact = validate($_POST['father_contact']);
    $mother_name = validate($_POST['mother_name']);
    $mother_occupation = validate($_POST['mother_occupation']);
    $mother_age = validate($_POST['mother_age']);
    $mother_contact = validate($_POST['mother_contact']);
    $guardian_name = validate($_POST['guardian_name']);
    $guardian_contact = validate($_POST['guardian_contact']);
    $guardian_relation = validate($_POST['guardian_relation']);
    $parent_income = validate($_POST['parent_income']);
    $siblings = validate($_POST['siblings']);

    // Medical History
    $height = validate($_POST['height']);
    $weight = validate($_POST['weight']);
    $blood_type = validate($_POST['blood_type']);
    $allergies = validate($_POST['allergies']);
    $conditions = validate($_POST['conditions']);
    $illness_history = validate($_POST['illness_history']);
    $current_medication = validate($_POST['current_medication']);
    $last_med_checkup = validate($_POST['last_med_checkup']);

    $communicable = $_POST['communicable'] ?? 'None';
    $communicable_name = ($communicable === 'Yes') ? $_POST['communicable_name'] : NULL;
    $communicable_medication = ($communicable === 'Yes') ? $_POST['communicable_medication'] : NULL;

    $mental_health = $_POST['mental_health'] ?? 'None';
    $mental_health_name = ($mental_health === 'Yes') ? $_POST['mental_health_name'] : NULL;
    $mental_health_medication = ($mental_health === 'Yes') ? $_POST['mental_health_medication'] : NULL;

    $physical = $_POST['physical'] ?? 'None';
    $physical_name = ($physical === 'Yes') ? $_POST['physical_name'] : NULL;
    $physical_medication = ($physical === 'Yes') ? $_POST['physical_medication'] : NULL;

    $menstrual_cycle = $_POST['menstrual_cycle'] ?? NULL;
    $reproductive_issue = $_POST['reproductive_issue'] ?? 'None';
    $reproductive_specify = ($reproductive_issue === 'Yes') ? $_POST['reproductive_specify'] : NULL;
    $reproductive_medication = ($reproductive_issue === 'Yes') ? $_POST['reproductive_medication'] : NULL;

    $pregnant = $_POST['pregnant'] ?? 'No';
    $last_checkup = ($pregnant === 'Yes') ? $_POST['last_checkup'] : NULL;
    $due_date = ($pregnant === 'Yes') ? $_POST['due_date'] : NULL;
    $physician_name = ($pregnant === 'Yes') ? $_POST['physician_name'] : NULL;
    $physician_contact = ($pregnant === 'Yes') ? $_POST['physician_contact'] : NULL;

    // Default password
    $default_password = password_hash('password123', PASSWORD_BCRYPT);

    // -------------------------------
    // 2️⃣ Profile Picture Upload
    // -------------------------------
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowed)) {
            header("Location: ../apply.php?error=Invalid file type. Only JPG, PNG, GIF allowed.");
            exit();
        }

        $newFileName = uniqid('profile_', true) . '.' . $fileExt;
        $uploadPath = "../assets/profile_pictures/" . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $uploadPath)) {
            header("Location: ../apply.php?error=Failed to upload profile picture.");
            exit();
        }

        $profile_picture = $newFileName;
    } else {
        header("Location: ../apply.php?error=Profile picture is required.");
        exit();
    }

    // -------------------------------
    // 3️⃣ Check Duplicate Student ID or Email
    // -------------------------------
    $stmtCheck = $conn->prepare("SELECT * FROM users WHERE student_id=? OR email=?");
    $stmtCheck->bind_param("ss", $student_id, $email);
    $stmtCheck->execute();
    $checkResult = $stmtCheck->get_result();
    if ($checkResult->num_rows > 0) {
        header("Location: ../apply.php?error=Student ID or Email already exists.");
        exit();
    }

    // -------------------------------
    // 4️⃣ Begin Transaction
    // -------------------------------
    mysqli_begin_transaction($conn);
    try {
        // Insert user
        $stmt1 = $conn->prepare("INSERT INTO users (student_id, password, email, profile_picture) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssss", $student_id, $default_password, $email, $profile_picture);
        $stmt1->execute();

        // Personal Info
        $stmt2 = $conn->prepare("INSERT INTO user_personal_information (student_id, full_name, nickname, age, sex, civil_status, nationality, contact, address, date_of_birth, place_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("sssisssisss", $student_id, $full_name, $nickname, $age, $sex, $civil_status, $nationality, $contact, $address, $date_of_birth, $place_of_birth);
        $stmt2->execute();

        // Educational Background
        $stmt3 = $conn->prepare("INSERT INTO user_educational_background (student_id, elem_school, elem_year, sec_school, sec_year, college_school, college_year, course, year_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("sssssssss", $student_id, $elem_school, $elem_year, $sec_school, $sec_year, $college_school, $college_year, $course, $year_level);
        $stmt3->execute();

        // Family Background
        $stmt4 = $conn->prepare("INSERT INTO user_family_background (student_id, father_name, father_occupation, father_age, father_contact, mother_name, mother_occupation, mother_age, mother_contact, guardian_name, guardian_contact, guardian_relation, parent_income, siblings) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt4->bind_param("sssiissiisisii", $student_id, $father_name, $father_occupation, $father_age, $father_contact, $mother_name, $mother_occupation, $mother_age, $mother_contact, $guardian_name, $guardian_contact, $guardian_relation, $parent_income, $siblings);
        $stmt4->execute();

        // Medical History
        $stmt5 = $conn->prepare("INSERT INTO user_medical_history (student_id, height, weight, blood_type, allergies, conditions, illness_history, current_medication, communicable, communicable_name, communicable_medication, mental_health, mental_health_name, mental_health_medication, physical, physical_name, physical_medication, last_med_checkup, menstrual_cycle, reproductive_issue, reproductive_specify, reproductive_medication, pregnant, last_checkup, due_date, physician_name, physician_contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt5->bind_param("ssissssssssssssssssssssssss", $student_id, $height, $weight, $blood_type, $allergies, $conditions, $illness_history, $current_medication, $communicable, $communicable_name, $communicable_medication, $mental_health, $mental_health_name, $mental_health_medication, $physical, $physical_name, $physical_medication, $last_med_checkup, $menstrual_cycle, $reproductive_issue, $reproductive_specify, $reproductive_medication, $pregnant, $last_checkup, $due_date, $physician_name, $physician_contact);
        $stmt5->execute();

        // Application Approvals
        $stmt6 = $conn->prepare("INSERT INTO application_approvals (student_id, application_date, status) VALUES (?, NOW(), 'Pending')");
        $stmt6->bind_param("s", $student_id);
        $stmt6->execute();

        mysqli_commit($conn);
        header("Location: ../apply.php?success=Application submitted successfully. Waiting for approval.");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: ../apply.php?error=Error submitting application: " . $e->getMessage());
        exit();
    }
}


// Update Information
if (isset($_POST['updateProfile'])) {

    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $nickname = $_POST['nickname'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Family
    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $guardian_relation = $_POST['guardian_relation'];
    $father_name = $_POST['father_name'];
    $father_contact = $_POST['father_contact'];
    $mother_name = $_POST['mother_name'];
    $mother_contact = $_POST['mother_contact'];
    $parent_income = $_POST['parent_income'];
    $siblings = $_POST['siblings'];

    // ===== PROFILE PICTURE =====
    $profile_picture_sql = "";

    if (!empty($_FILES['profile_picture']['name'])) {
        $file = $_FILES['profile_picture'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = "profile_" . $student_id . "_" . time() . "." . $ext;
        $target = "../assets/profile_pictures/" . $newName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $profile_picture_sql = ", profile_picture='$newName'";
        } else {
            header("Location: ../student/profile.php?error=Failed to upload profile picture.");
            exit();
        }
    }

    // Update Personal Info
    $sql1 = "UPDATE user_personal_information 
            SET full_name='$full_name',
                nickname='$nickname',
                contact='$contact',
                address='$address'
            WHERE student_id='$student_id'";
    $result1 = mysqli_query($conn, $sql1);

    // Update Family
    $sql2 = "UPDATE user_family_background 
            SET guardian_name='$guardian_name',
                guardian_contact='$guardian_contact',
                guardian_relation='$guardian_relation',
                father_name='$father_name',
                father_contact='$father_contact',
                mother_name='$mother_name',
                mother_contact='$mother_contact',
                parent_income='$parent_income',
                siblings='$siblings'
            WHERE student_id='$student_id'";
    $result2 = mysqli_query($conn, $sql2);

    if ($result1 && $result2) {
        $sql3 = "UPDATE users 
                SET email='$email'
                $profile_picture_sql
                WHERE student_id='$student_id'";
        $result3 = mysqli_query($conn, $sql3);

        if ($result3) {
            header("Location: ../student/profile.php?success=Profile updated successfully.");
            exit();
        }
    }

    header("Location: ../student/profile.php?error=Error updating profile.");
    exit();
}

// Update Password
if (isset($_POST['updatePassword'])) {
    $student_id = $_POST['student_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current hashed password from database
    $sql = "SELECT password FROM users WHERE student_id='$student_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];

    // Verify current password
    if (password_verify($current_password, $hashed_password)) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update password in database
            $sql_update = "UPDATE users SET password='$new_hashed_password' WHERE student_id='$student_id'";
            $result_update = mysqli_query($conn, $sql_update);

            if ($result_update) {
                header("Location: ../student/profile.php?success=Password changed successfully.");
                exit();
            } else {
                header("Location: ../student/profile.php?error=Error updating password. Please try again.");
                exit();
            }
        } else {
            header("Location: ../student/profile.php?error=New password and confirm password do not match.");
            exit();
        }
    } else {
        header("Location: ../student/profile.php?error=Current password is incorrect.&student_id=$student_id");
        exit();
    }
}

// Save Signed Contract
if (isset($_POST['signature']) && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $signature_data = $_POST['signature'];
    $semester = $_POST['sem'];
    $school_year = $_POST['school_year'];
    $contract_start = $_POST['start_month'];
    $contract_end = $_POST['end_month'];

    // Decode the base64 encoded image
    $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
    $signature_data = str_replace(' ', '+', $signature_data);
    $signature_image = base64_decode($signature_data);

    // Save the signature image to a file
    $file_path = '../signatures/' . $student_id . '_signature.png';
    file_put_contents($file_path, $signature_image);

    // Insert contract record into database
    $sql = "INSERT INTO contracts (student_id, signature_path, status, signed_at, semester, school_year, contract_start, contract_end) VALUES ('$student_id', '$file_path', 'Signed', NOW(), '$semester', '$school_year', '$contract_start', '$contract_end')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: ../student/dashboard.php?success=Contract signed successfully.");
        exit();
    } else {
        header("Location: ../student/sign_contract.php?error=Error saving contract. Please try again.");
        exit();
    }
}

// Add New Room
if (isset($_POST['room_save'])) {
    $room_number = $_POST['room_number'];
    $room_capacity = $_POST['room_capacity'];
    $room_status = $_POST['room_status'];

    $sql = "INSERT INTO rooms (room_number, capacity, status) VALUES ('$room_number', '$room_capacity', '$room_status')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: ../admin/rooms.php?success=Room added successfully.");
        exit();
    } else {
        header("Location: ../admin/rooms.php?error=Error adding room. Please try again.");
        exit();
    }
}

// Assign Students to Room
if (isset($_POST['assignRoom'])) {
    $room_id = $_POST['room_id'];
    $students = $_POST['students'] ?? [];

    if (empty($students)) {
        header("Location: ../admin/rooms.php?error=No students selected for assignment.");
        exit();
    }

    foreach ($students as $student_id) {
        mysqli_query($conn, "INSERT INTO room_assignments (student_id, room_num, assigned_at) VALUES ('$student_id', '$room_id', NOW())");
        mysqli_query($conn, "UPDATE rooms SET occupied = occupied + 1 WHERE room_number = '$room_id'");

        // Get room capacity & occupied
        $roomQuery = mysqli_query($conn, "
        SELECT capacity, occupied 
        FROM rooms 
        WHERE room_number = '$room_id'
    ");
        $roomData = mysqli_fetch_assoc($roomQuery);

        if ($roomData['occupied'] >= $roomData['capacity']) {
            mysqli_query($conn, "UPDATE rooms SET status='Full' WHERE room_number = '$room_id'");
        }
    }

    header("Location: ../admin/rooms.php?success=Students assigned to room successfully.");
    exit();
}

if (isset($_POST['updateRoom'])) {
    $id = $_POST['room_id'];
    $room = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    mysqli_query($conn, "
        UPDATE rooms
        SET room_number='$room',
            capacity='$capacity',
            status='$status'
        WHERE id='$id'
    ");

    $roomQuery = mysqli_query($conn, "
        SELECT capacity, occupied 
        FROM rooms 
        WHERE room_number = '$room'
    ");
    $roomData = mysqli_fetch_assoc($roomQuery);

    if ($roomData['occupied'] >= $roomData['capacity']) {
        mysqli_query($conn, "UPDATE rooms SET status='Full' WHERE room_number = '$room'");
    } else {
        mysqli_query($conn, "UPDATE rooms SET status='Available' WHERE room_number = '$room'");
    }

    header("Location: ../admin/rooms.php");
    exit;
}

if (isset($_POST['deleteRoom'])) {
    $id = $_POST['room_id'];

    mysqli_query($conn, "DELETE FROM rooms WHERE id='$id'");

    header("Location: ../admin/rooms.php");
    exit;
}

// UPDATE CONTRACT
if (isset($_POST['updateContract'])) {
    $id = $_POST['contract_id'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE contracts SET status='$status' WHERE id='$id'");
    header("Location: ../admin/contracts.php");
    exit;
}

// DELETE CONTRACT
if (isset($_POST['deleteContract'])) {
    $id = $_POST['contract_id'];

    mysqli_query($conn, "DELETE FROM contracts WHERE id='$id'");
    header("Location: ../admin/contracts.php");
    exit;
}

// ===== Maintenance Request =====
if (isset($_POST['issue']) && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $issue = trim($_POST['issue']);

    if (empty($issue)) {
        echo json_encode(['status' => 'error', 'message' => 'Please describe the issue.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO maintenance_requests (student_id, issue, status, requested_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("ss", $student_id, $issue);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Maintenance request submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
}

// 
if (isset($_POST['subject']) && isset($_POST['details']) && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $subject = trim($_POST['subject']);
    $details = trim($_POST['details']);

    if (empty($subject) || empty($details)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO complaints (student_id, subject, details, status, submitted_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("sss", $student_id, $subject, $details);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Complaint submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
}

if ($_GET['action'] === 'upload_receipt') {
    $student_id = $_POST['student_id'] ?? 0;
    $month_paid = $_POST['month_paid'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $file = $_FILES['receipt_file'] ?? null;

    if (!$month_paid || !$amount || !$file) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Save file
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = "receipt_{$student_id}_" . time() . ".{$ext}";
    $targetDir = "../receipts/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $targetPath = $targetDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO payment_receipts (student_id, amount, month_paid, receipt_path, status, paid_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("sdss", $student_id, $amount, $month_paid, $targetPath);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Receipt uploaded successfully! Awaiting admin approval.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    exit;
}

if ($_GET['action'] === 'update_receipt_status') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    $status = $input['status'] ?? '';

    if (!$id || !in_array($status, ['Approved', 'Rejected'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE payment_receipts SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Receipt status updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
}

// Maintenance Requests
if (isset($_POST['resolve_maintenance'])) {
    $id = $_POST['maintenance_id'];
    $stmt = $conn->prepare("UPDATE maintenance_requests SET status='Resolved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: ../admin/requests.php");
    exit;
}

// Complaints
if (isset($_POST['resolve_complaint'])) {
    $id = $_POST['complaint_id'];
    $stmt = $conn->prepare("UPDATE complaints SET status='Addressed' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: ../admin/requests.php");
    exit;
}

// Passlip Requests
if (isset($_POST['approve_passlip'])) {
    $id = $_POST['passlip_id'];

    // Generate secure verification code & expiry
    $code = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 day'));

    // Update DB
    $stmt = $conn->prepare("
        UPDATE passlips 
        SET status='Approved',
            verification_code=?,
            expires_at=?,
            used=0
        WHERE id=?");
    $stmt->bind_param("ssi", $code, $expires, $id);
    $stmt->execute();

    // Fetch user + pass slip details
    $emailQuery = $conn->prepare("
        SELECT 
            u.email,
            upi.full_name,
            p.departure_date,
            p.departure_time,
            p.destination,
            p.purpose,
            p.return_date,
            p.return_time,
            p.verification_code
        FROM users u
        JOIN user_personal_information upi ON u.student_id = upi.student_id
        JOIN passlips p ON u.student_id = p.student_id
        WHERE p.id = ?");
    $emailQuery->bind_param("i", $id);
    $emailQuery->execute();
    $userData = $emailQuery->get_result()->fetch_assoc();

    // ENV
    $isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');
    $baseUrl = $isLocal
        ? "http://localhost/iDORM"
        : "https://idorm.ispsc.edu.ph";

    $verifyUrl = $baseUrl . "/verify_passlip.php?code=" . $userData['verification_code'];

    // SEND EMAIL
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'idormsystem@gmail.com';
        $mail->Password = 'yyoyfrqcybomxhkj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('idormsystem@gmail.com', 'IDORM System');
        $mail->addAddress($userData['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Official Pass Slip – Approved';

        // Embed logo
        $mail->addEmbeddedImage('../assets/img/circle-logo.png', 'idormlogo');

        $mail->Body = "
<!DOCTYPE html>
<html>
<body style='margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;'>
<table width='100%' style='background:#f4f6f9;padding:20px;'>
<tr><td align='center'>
<table width='600' style='background:#ffffff;border-radius:8px;overflow:hidden;'>

<tr>
<td style='padding:20px; border-bottom:1px solid #eee;'>
<table width='100%'><tr>
<td width='60'>
<img src='cid:idormlogo' width='50' alt='IDORM Logo'>
</td>
<td>
<h2 style='margin:0;'>IDORM System</h2>
<p style='margin:0; font-size:12px; color:#888;'>Ilocos Sur Polytechnic State College</p>
</td>
</tr></table>
</td>
</tr>

<tr>
<td style='padding:30px;'>
<h3>Hello {$userData['full_name']},</h3>

<p style='font-size:14px;'>
Your <b>Pass Slip Request</b> has been:</p>

<p style='font-size:18px; color:#16a34a; font-weight:bold;'>APPROVED</p>
<p style='font-size:13px; color:#555;'>Approved by Dorm Manager via IDORM System.</p>

<hr>

<h4>Pass Slip Details</h4>
<table width='100%' cellpadding='6' cellspacing='0' style='border-collapse:collapse;font-size:14px;'>
<tr><td><b>Departure Date</b></td><td>{$userData['departure_date']}</td></tr>
<tr><td><b>Departure Time</b></td><td>{$userData['departure_time']}</td></tr>
<tr><td><b>Destination</b></td><td>{$userData['destination']}</td></tr>
<tr><td><b>Purpose</b></td><td>{$userData['purpose']}</td></tr>
<tr><td><b>Return Date</b></td><td>{$userData['return_date']}</td></tr>
<tr><td><b>Return Time</b></td><td>{$userData['return_time']}</td></tr>
</table>

<p style='margin-top:20px;font-size:13px;color:#555;'>
Please present this email to the <b>Dormitory Guard</b> upon exit and re-entry.
</p>

<p style='margin-top:20px; margin-bottom:20px;'>You can also verify this pass slip online:<br>
<a href='{$verifyUrl}'><button style='background:#16a34a;color:white;border:none;padding:8px 16px;border-radius:4px;text-decoration:none;'>Verify Pass Slip</button></a></p>

<p style='margin-top:20px;'>Best regards,<br><b>IDORM System</b></p>
</td>
</tr>

<tr>
<td style='background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#777;'>
<p>© " . date('Y') . " IDORM System | Ilocos Sur Polytechnic State College</p> 
<p>Vigan City, Ilocos Sur</p>
<p style='font-size:11px;color:#aaa;'>This is an automated email. Please do not reply.</p>
</td>
</tr>

</table>
</td></tr>
</table>
</body>
</html>";

        $mail->send();
    } catch (Exception $e) {
        // Log error
        echo "<script>console.error('Mailer Error: " . $mail->ErrorInfo . "');</script>";
        exit;
    }

    header("Location: ../admin/requests.php");
    exit;
}


if (isset($_POST['reject_passlip'])) {
    $id = $_POST['passlip_id'];

    // Update status in DB
    $stmt = $conn->prepare("UPDATE passlips SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Fetch user + pass slip details
    $emailQuery = $conn->prepare("
        SELECT 
            u.email,
            upi.full_name,
            p.departure_date,
            p.departure_time,
            p.destination,
            p.purpose,
            p.return_date,
            p.return_time
        FROM users u
        JOIN user_personal_information upi ON u.student_id = upi.student_id
        JOIN passlips p ON u.student_id = p.student_id
        WHERE p.id = ?");
    $emailQuery->bind_param("i", $id);
    $emailQuery->execute();
    $userData = $emailQuery->get_result()->fetch_assoc();

    // ENV
    $isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');
    $baseUrl = $isLocal
        ? "https://your-ngrok-link.ngrok-free.app"
        : "https://idorm.ispsc.edu.ph";

    // SEND EMAIL
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'idormsystem@gmail.com';
        $mail->Password = 'yyoyfrqcybomxhkj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('idormsystem@gmail.com', 'IDORM System');
        $mail->addAddress($userData['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Official Pass Slip – Rejected';

        // Optional: embed logo
        $mail->addEmbeddedImage('../assets/img/circle-logo.png', 'idormlogo');

        $mail->Body = "
<!DOCTYPE html>
<html>
<body style='margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;'>
<table width='100%' style='background:#f4f6f9;padding:20px;'>
<tr><td align='center'>
<table width='600' style='background:#ffffff;border-radius:8px;overflow:hidden;'>

<tr>
<td style='padding:20px; border-bottom:1px solid #eee;'>
<table width='100%'><tr>
<td width='60'>
<img src='cid:idormlogo' width='50' alt='IDORM Logo'>
</td>
<td>
<h2 style='margin:0;'>IDORM System</h2>
<p style='margin:0; font-size:12px; color:#888;'>Ilocos Sur Polytechnic State College</p>
</td>
</tr></table>
</td>
</tr>

<tr>
<td style='padding:30px;'>
<h3>Hello {$userData['full_name']},</h3>

<p style='font-size:14px;'>
Your <b>Pass Slip Request</b> has been:</p>

<p style='font-size:18px; color:#dc2626; font-weight:bold;'>REJECTED</p>
<p style='font-size:13px; color:#555;'>Processed by Dorm Manager via IDORM System.</p>

<hr>

<h4>Pass Slip Details</h4>
<table width='100%' cellpadding='6' cellspacing='0' style='border-collapse:collapse;font-size:14px;'>
<tr><td><b>Departure Date</b></td><td>{$userData['departure_date']}</td></tr>
<tr><td><b>Departure Time</b></td><td>{$userData['departure_time']}</td></tr>
<tr><td><b>Destination</b></td><td>{$userData['destination']}</td></tr>
<tr><td><b>Purpose</b></td><td>{$userData['purpose']}</td></tr>
<tr><td><b>Return Date</b></td><td>{$userData['return_date']}</td></tr>
<tr><td><b>Return Time</b></td><td>{$userData['return_time']}</td></tr>
</table>

<p style='margin-top:20px;font-size:13px;color:#555;'>
Please contact the <b>Dormitory Office</b> for clarification regarding your pass slip request.
</p>

<p style='margin-top:20px;'>Best regards,<br><b>IDORM System</b></p>
</td>
</tr>

<tr>
<td style='background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#777;'>
<p>© " . date('Y') . " IDORM System | Ilocos Sur Polytechnic State College</p>
<p>Vigan City, Ilocos Sur</p>
<p style='font-size:11px;color:#aaa;'>This is an automated email. Please do not reply.</p>
</td>
</tr>

</table>
</td></tr>
</table>
</body>
</html>";

        $mail->send();
    } catch (Exception $e) {
        // Log error if needed
    }

    header("Location: ../admin/requests.php");
    exit;
}


if (isset($_POST['add_announcement'])) {

    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (empty($title) || empty($message)) {
        header("Location: ../admin/announcements.php?error=empty");
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO announcements (title, description) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $title, $message);

    if ($stmt->execute()) {
        header("Location: ../admin/announcements.php?success=added");
    } else {
        header("Location: ../admin/announcements.php?error=failed");
    }

    $stmt->close();
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT ANNOUNCEMENT
|--------------------------------------------------------------------------
*/
if (isset($_POST['edit_announcement'])) {

    $id = $_POST['announcement_id'];
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (empty($id) || empty($title) || empty($message)) {
        header("Location: ../admin/announcements.php?error=empty");
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE announcements SET title = ?, description = ? WHERE id = ?"
    );
    $stmt->bind_param("ssi", $title, $message, $id);

    if ($stmt->execute()) {
        header("Location: ../admin/announcements.php?success=updated");
    } else {
        header("Location: ../admin/announcements.php?error=failed");
    }

    $stmt->close();
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE ANNOUNCEMENT
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_announcement'])) {

    $id = $_POST['announcement_id'];

    if (empty($id)) {
        header("Location: ../admin/announcements.php?error=invalid");
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../admin/announcements.php?success=deleted");
    } else {
        header("Location: ../admin/announcements.php?error=failed");
    }

    $stmt->close();
    exit;
}

$action = $_GET['action'] ?? '';

/* ---------------- PREVIEW ---------------- */
if ($action === 'preview_report') {
    $data = json_decode(file_get_contents("php://input"), true);
    $type = $data['type'];
    $status = $data['status'];
    $month = $data['month'];

    $query = buildReportQuery($type, $status, $month, false);
    $result = mysqli_query($conn, $query);

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    echo json_encode($rows);
    exit;
}

/* ---------------- DOWNLOAD ---------------- */
if ($action === 'download_report') {
    $type = $_GET['type'];
    $status = $_GET['status'];
    $month = $_GET['month'];
    $format = $_GET['format'];

    $query = buildReportQuery($type, $status, $month, true);
    $result = mysqli_query($conn, $query);

    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename={$type}_report.csv");
        $out = fopen("php://output", "w");

        $first = mysqli_fetch_assoc($result);
        fputcsv($out, array_keys($first));
        fputcsv($out, $first);

        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    if ($format === 'pdf') {
        $html = "<h2 style='text-align:center;'>" . ucfirst($type) . " Report</h2><table border='1' width='100%'><tr>";
        $first = mysqli_fetch_assoc($result);
        foreach (array_keys($first) as $h) {
            $html .= "<th>$h</th>";
        }
        $html .= "</tr><tr>";
        foreach ($first as $v) $html .= "<td>$v</td>";
        $html .= "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            $html .= "<tr>";
            foreach ($row as $v) $html .= "<td>$v</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("{$type}_report.pdf", ["Attachment" => true]);
        exit;
    }
}

/* ---------------- QUERY BUILDER ---------------- */
function buildReportQuery($type, $status, $month, $forDownload)
{
    switch ($type) {
        case 'students':
            return "SELECT student_id, full_name, address, contact FROM user_personal_information";

        case 'payments':
            $q = "SELECT student_id, month_paid, status FROM payment_receipts WHERE 1";
            if ($status) $q .= " AND status='$status'";
            if ($month) $q .= " AND month_paid LIKE '%$month%'";
            return $q;

        case 'passlips':
            $q = "SELECT student_id, destination, purpose, departure_date, return_date, status FROM passlips WHERE 1";
            if ($status) $q .= " AND status='$status'";
            return $q;

        case 'rooms':
            return "SELECT room_number, capacity, current_occupants FROM rooms";

        case 'contracts':
            $q = "SELECT student_id, status, signed_at FROM contracts WHERE 1";
            if ($status) $q .= " AND status='$status'";
            return $q;

        case 'applications':
            $q = "SELECT student_id, status, date_updated FROM application_approvals WHERE 1";
            if ($status) $q .= " AND status='$status'";
            return $q;
    };
};

// ================= INVENTORY =================
if ($action === 'add_inventory') {
    $item_name = $_POST['item_name'];
    $category  = $_POST['category'];
    $quantity  = $_POST['quantity'];
    $status    = $_POST['status'];
    $location  = $_POST['location'];
    $remarks   = $_POST['remarks'];

    mysqli_query($conn, "
        INSERT INTO inventory 
        (item_name, category, quantity, status, location, remarks)
        VALUES 
        ('$item_name','$category','$quantity','$status','$location','$remarks')
    ");

    echo json_encode(['status' => 'success']);
    exit;
}

// ================= UPDATE INVENTORY =================
if ($action === 'update_inventory') {
    $id        = $_POST['id'];
    $item_name = $_POST['item_name'];
    $category  = $_POST['category'];
    $quantity  = $_POST['quantity'];
    $status    = $_POST['status'];
    $location  = $_POST['location'];
    $remarks   = $_POST['remarks'];

    mysqli_query($conn, "
        UPDATE inventory SET
            item_name = '$item_name',
            category  = '$category',
            quantity  = '$quantity',
            status    = '$status',
            location  = '$location',
            remarks   = '$remarks'
        WHERE id = '$id'
    ");

    echo json_encode(['status' => 'success']);
    exit;
}


if ($action === 'delete_inventory') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    mysqli_query($conn, "DELETE FROM inventory WHERE id = '$id'");
    echo json_encode(['status' => 'success']);
    exit;
}
