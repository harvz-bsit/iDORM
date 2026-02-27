<?php
include '../config/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->query("SELECT * FROM application_approvals WHERE student_id = '$id'");
    $row = $query->fetch_assoc();

    // Fetch personal information
    $query = "SELECT * FROM user_personal_information WHERE student_id='$id'";
    $result_info = mysqli_query($conn, $query);
    $row_info = mysqli_fetch_assoc($result_info);

    // Fetch email
    $email_query = "SELECT * FROM users WHERE student_id='$id'";
    $email_result = mysqli_query($conn, $email_query);
    $email_row = mysqli_fetch_assoc($email_result);

    // Fetch Medical History
    $med_query = "SELECT * FROM user_medical_history WHERE student_id='$id'";
    $med_result = mysqli_query($conn, $med_query);
    $med_row = mysqli_fetch_assoc($med_result);

    // Fetch Family Background
    $fam_query = "SELECT * FROM user_family_background WHERE student_id='$id'";
    $fam_result = mysqli_query($conn, $fam_query);
    $fam_row = mysqli_fetch_assoc($fam_result);

    // Fetch Educational Background
    $edu_query = "SELECT * FROM user_educational_background WHERE student_id='$id'";
    $edu_result = mysqli_query($conn, $edu_query);
    $edu_row = mysqli_fetch_assoc($edu_result);

    if (!$row) {
        echo "<p class='text-center text-danger'>Applicant not found.</p>";
        exit;
    }
?>
    <div class="row g-3 mt-1">
        <h5 class="fw-bold text-maroon mb-1">Personal Information</h5>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Full Name:</label>
            <p><?= htmlspecialchars($row_info['full_name']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Nickname:</label>
            <p><?= htmlspecialchars($row_info['nickname']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Age:</label>
            <p><?= htmlspecialchars($row_info['age']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Sex:</label>
            <p><?= htmlspecialchars($row_info['sex']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Civil Status:</label>
            <p><?= htmlspecialchars($row_info['civil_status']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Nationality:</label>
            <p><?= htmlspecialchars($row_info['nationality']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Address:</label>
            <p><?= htmlspecialchars($row_info['address']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Birthdate:</label>
            <p><?= htmlspecialchars($row_info['date_of_birth']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Birthplace:</label>
            <p><?= htmlspecialchars($row_info['place_of_birth']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Contact Number:</label>
            <p><?= htmlspecialchars($row_info['contact']) ?></p>
        </div>
        <div class="col-md-12 border border-dark m-0">
            <label class="fw-semibold text-maroon">Email Address:</label>
            <p><?= htmlspecialchars($email_row['email']) ?></p>
        </div>

        <h5 class="fw-bold text-maroon mt-3">Educational Background</h5>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Elementary</label>
            <p><?= htmlspecialchars($edu_row['elem_school']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Year Graduated:</label>
            <p><?= htmlspecialchars($edu_row['elem_year']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Secondary</label>
            <p><?= htmlspecialchars($edu_row['sec_school']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Year Graduated:</label>
            <p><?= htmlspecialchars($edu_row['sec_year']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">College</label>
            <p><?= htmlspecialchars($edu_row['college_school']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Year Graduated:</label>
            <p><?= htmlspecialchars($edu_row['college_year']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Current Course:</label>
            <p><?= htmlspecialchars($edu_row['course']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Year Level:</label>
            <p><?= htmlspecialchars($edu_row['year_level']) ?></p>
        </div>

        <h5 class="fw-bold text-maroon mt-3">Family Background</h5>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Father's Full Name</label>
            <p><?= htmlspecialchars($fam_row['father_name']) ?></p>
        </div>
        <div class="col-md-2 border border-dark m-0">
            <label class="fw-semibold text-maroon">Age:</label>
            <p><?= htmlspecialchars($fam_row['father_age']) ?></p>
        </div>
        <div class="col-md-2 border border-dark m-0">
            <label class="fw-semibold text-maroon">Occupation</label>
            <p><?= htmlspecialchars($fam_row['father_occupation']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Contact Number:</label>
            <p><?= htmlspecialchars($fam_row['father_contact']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Mother's Full Name</label>
            <p><?= htmlspecialchars($fam_row['mother_name']) ?></p>
        </div>
        <div class="col-md-2 border border-dark m-0">
            <label class="fw-semibold text-maroon">Age:</label>
            <p><?= htmlspecialchars($fam_row['mother_age']) ?></p>
        </div>
        <div class="col-md-2 border border-dark m-0">
            <label class="fw-semibold text-maroon">Occupation</label>
            <p><?= htmlspecialchars($fam_row['mother_occupation']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Contact Number:</label>
            <p><?= htmlspecialchars($fam_row['mother_contact']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Guardian's Full Name</label>
            <p><?= htmlspecialchars($fam_row['guardian_name']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Relationship</label>
            <p><?= htmlspecialchars($fam_row['guardian_relation']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Contact Number:</label>
            <p><?= htmlspecialchars($fam_row['guardian_contact']) ?></p>
        </div>
        <div class="col-md-12 border border-dark m-0">
            <label class="fw-semibold text-maroon">Siblings</label>
            <p><?= htmlspecialchars($fam_row['siblings']) ?></p>
        </div>

        <h5 class="fw-bold text-maroon mt-3">Medical History</h5>
        <div class="col-md-3 border border-dark m-0">
            <label class="fw-semibold text-maroon">Height:</label>
            <p><?= htmlspecialchars($med_row['height']) ?></p>
        </div>
        <div class="col-md-3 border border-dark m-0">
            <label class="fw-semibold text-maroon">Weight:</label>
            <p><?= htmlspecialchars($med_row['weight']) ?></p>
        </div>
        <div class="col-md-3 border border-dark m-0">
            <label class="fw-semibold text-maroon">Blood Type</label>
            <p><?= htmlspecialchars($med_row['blood_type']) ?></p>
        </div>
        <div class="col-md-3 border border-dark m-0">
            <label class="fw-semibold text-maroon">Allergies:</label>
            <p><?= htmlspecialchars($med_row['allergies']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Medical Conditions:</label>
            <p><?= htmlspecialchars($med_row['conditions']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">History of Serious Illness:</label>
            <p><?= htmlspecialchars($med_row['illness_history']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Current Medication</label>
            <p><?= htmlspecialchars($med_row['current_medication']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Communicable Diseases:</label>
            <p><?= htmlspecialchars($med_row['communicable']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Communicable Name:</label>
            <p><?= htmlspecialchars($med_row['communicable_name']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Communicable Medication:</label>
            <p><?= htmlspecialchars($med_row['communicable_medication']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Mental Health Problem:</label>
            <p><?= htmlspecialchars($med_row['mental_health']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Mental Health Name:</label>
            <p><?= htmlspecialchars($med_row['mental_health_name']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Mental Health Medication:</label>
            <p><?= htmlspecialchars($med_row['mental_health_medication']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Physical Disability:</label>
            <p><?= htmlspecialchars($med_row['physical']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Disability Name:</label>
            <p><?= htmlspecialchars($med_row['physical_name']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Disability Medication:</label>
            <p><?= htmlspecialchars($med_row['physical_medication']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Last Medical Check-up:</label>
            <p><?= htmlspecialchars($med_row['last_med_checkup']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Menstrual Cycle:</label>
            <p><?= htmlspecialchars($med_row['menstrual_cycle']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Reproductive Issue:</label>
            <p><?= htmlspecialchars($med_row['reproductive_issue']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Specific Issue:</label>
            <p><?= htmlspecialchars($med_row['reproductive_specify']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Reproductive Medication:</label>
            <p><?= htmlspecialchars($med_row['reproductive_medication']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Pregnant?:</label>
            <p><?= htmlspecialchars($med_row['pregnant']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Last Checkup:</label>
            <p><?= htmlspecialchars($med_row['last_checkup']) ?></p>
        </div>
        <div class="col-md-4 border border-dark m-0">
            <label class="fw-semibold text-maroon">Due Date:</label>
            <p><?= htmlspecialchars($med_row['due_date']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Physician Name</label>
            <p><?= htmlspecialchars($med_row['physician_name']) ?></p>
        </div>
        <div class="col-md-6 border border-dark m-0">
            <label class="fw-semibold text-maroon">Contact Number:</label>
            <p><?= htmlspecialchars($med_row['physician_contact']) ?></p>
        </div>
    </div>
<?php
}
?>