<?php
include '../config/conn.php';

// Login Using Student ID and Password (Hashed)
if (isset($_POST['login'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE student_id='$student_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
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
        // Student ID not found
        header("Location: ../login.php?error=Incorrect Student ID or Password");
        exit();
    }
}

// Dormitory Application Submission & User Creation
if (isset($_POST['submit'])) {
    // Validation
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
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
    $last_med_checkup = validate($_POST['last_med_checkup']);
    $illness_history = validate($_POST['illness_history']);
    $current_medication = validate($_POST['current_medication']);
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

    // Default Password
    $default_password = password_hash('password123', PASSWORD_BCRYPT);

    // Create User Based on Student ID
    $sql = "INSERT INTO users (student_id, password, email) VALUES ('$student_id', '$default_password', '$email')";
    $result = mysqli_query($conn, $sql);

    // Insert all information after successful user creation
    if ($result) {
        $sql2 = "INSERT INTO user_personal_information (student_id, full_name, nickname, age, sex, civil_status, nationality, contact, address, date_of_birth, place_of_birth) 
                 VALUES ('$student_id', '$full_name', '$nickname', '$age', '$sex', '$civil_status', '$nationality', '$contact', '$address', '$date_of_birth', '$place_of_birth')";
        $result2 = mysqli_query($conn, $sql2);

        $sql3 = "INSERT INTO user_educational_background (student_id, elem_school, elem_year, sec_school, sec_year, college_school, college_year, course, year_level) 
                 VALUES ('$student_id', '$elem_school', '$elem_year', '$sec_school', '$sec_year', '$college_school', '$college_year', '$course', '$year_level')";
        $result3 = mysqli_query($conn, $sql3);

        $sql4 = "INSERT INTO user_family_background (student_id, father_name, father_occupation, father_age, father_contact, mother_name, mother_occupation, mother_age, mother_contact, guardian_name, guardian_contact, guardian_relation, parent_income, siblings) 
                 VALUES ('$student_id', '$father_name', '$father_occupation', '$father_age', '$father_contact', '$mother_name', '$mother_occupation', '$mother_age', '$mother_contact', '$guardian_name', '$guardian_contact', '$guardian_relation', '$parent_income', '$siblings')";
        $result4 = mysqli_query($conn, $sql4);

        $sql5 = "INSERT INTO user_medical_history (student_id, height, weight, blood_type, allergies, conditions, illness_history, current_medication, communicable, communicable_name, communicable_medication, mental_health, mental_health_name, mental_health_medication, physical, physical_name, physical_medication, last_med_checkup menstrual_cycle, reproductive_issue, reproductive_specify, reproductive_medication, last_checkup, due_date, physician_name, physician_contact) 
                 VALUES ('$student_id', '$height', '$weight', '$blood_type', '$allergies', '$conditions', '$illness_history', '$current_medication', '$communicable', '$communicable_name', '$communicable_medication', '$mental_health', '$mental_health_name', '$mental_health_medication', '$physical', '$physical_name', '$last_med_checkup', '$physical_medication', '$menstrual_cycle', '$reproductive_issue', '$reproductive_specify', '$reproductive_medication', '$last_checkup', '$due_date', '$physician_name', '$physician_contact')";
        $result5 = mysqli_query($conn, $sql5);

        $sql6 = "INSERT INTO application_approvals (student_id, application_date, status, remarks) 
                 VALUES ('$student_id', NOW(), 'Pending', 'Your application is under review.')";
        $result6 = mysqli_query($conn, $sql6);

        if ($result2 && $result3 && $result4 && $result5 && $result6) {
            header("Location: ../apply.php?success=Application submitted successfully. Waiting for approval.");
            exit();
        } else {
            header("Location: ../apply.php?error=Error submitting application. Please try again.");
            exit();
        }
    } else {
        header("Location: ../apply.php?error=Error creating user. Please try again.");
        exit();
    }
}

// Update Information
if (isset($_POST['updateProfile'])) {
    // Personal Information
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $nickname = $_POST['nickname'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    //Family Background
    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $guardian_relation = $_POST['guardian_relation'];
    $father_name = $_POST['father_name'];
    $father_contact = $_POST['father_contact'];
    $mother_name = $_POST['mother_name'];
    $mother_contact = $_POST['mother_contact'];
    $parent_income = $_POST['parent_income'];
    $siblings = $_POST['siblings'];

    // Update Personal Information
    $sql1 = "UPDATE user_personal_information SET full_name='$full_name', nickname='$nickname', contact='$contact', address='$address' WHERE student_id='$student_id'";
    $result1 = mysqli_query($conn, $sql1);

    // Update Family Background
    $sql2 = "UPDATE user_family_background SET guardian_name='$guardian_name', guardian_contact='$guardian_contact', guardian_relation='$guardian_relation', father_name='$father_name', father_contact='$father_contact', mother_name='$mother_name', mother_contact='$mother_contact', parent_income='$parent_income', siblings='$siblings' WHERE student_id='$student_id'";
    $result2 = mysqli_query($conn, $sql2);

    if ($result1 && $result2) {
        // Update Email in Users Table
        $sql3 = "UPDATE users SET email='$email' WHERE student_id='$student_id'";
        $result3 = mysqli_query($conn, $sql3);

        if ($result3) {
            header("Location: ../student/profile.php?success=Profile updated successfully.");
            exit();
        } else {
            header("Location: ../student/profile.php?error=Error updating email. Please try again.");
            exit();
        }
    } else {
        header("Location: ../student/profile.php?error=Error updating profile. Please try again.");
        exit();
    }
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
