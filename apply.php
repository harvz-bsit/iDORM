<?php
session_start();
if (isset($_SESSION['student_id'])) {
    header("Location: student/dashboard.php");
    exit;
}

// SweetAlert messages
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Room | IDORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/circle-logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --maroon: #7a1e1e;
            --green: #44693D;
            --gold: #e3b23c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, rgba(86, 24, 24, 0.8), rgba(52, 65, 42, 0.8), rgba(112, 93, 49, 0.7)),
                url('assets/img/dorm-bg.jpg') center/cover no-repeat;
            backdrop-filter: blur(5px);
            padding: 40px;
        }

        .apply-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 700px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
            animation: fadeUp 1s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control {
            background: rgba(255, 255, 255, 0.12);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px var(--gold);
            background: rgba(255, 255, 255, 0.18);
        }

        .btn-apply {
            background: linear-gradient(135deg, var(--gold), var(--green));
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-apply:hover {
            background: linear-gradient(135deg, var(--green), var(--gold));
            transform: translateY(-3px);
        }

        h3 {
            color: var(--gold);
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: 500;
            color: var(--gold);
            margin-bottom: 6px;
        }

        .apply-card img {
            height: 80px;
            margin-bottom: 15px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .btn-nav {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-nav:hover {
            background: var(--gold);
            color: #000;
        }
    </style>
</head>

<body>

    <div class="apply-card">
        <div class="text-center">
            <img src="assets/img/logo.png" alt="IDORM Logo">
        </div>
        <h3>Dormitory Application Form</h3>
        <div class="text-center text-light mb-4">
            <small>Kindly fill out the application form and provide complete information accurately</small><br>
            <small>Put N/A if not applicable</small>
        </div>

        <form id="applicationForm" action="includes/processes.php" method="POST" enctype="multipart/form-data">
            <!-- STEP 1: PERSONAL INFO -->
            <div class="step active">
                <div class="row">
                    <h5 class="text-light mb-3">Personal Information</h5>

                    <div class="col-md-12">
                        <label>Student ID</label>
                        <input type="text" name="student_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Nickname</label>
                        <input type="text" name="nickname" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Sex</label>
                        <select name="sex" class="form-control" required>
                            <option value=""></option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Civil Status</label>
                        <select name="civil_status" class="form-control" required>
                            <option value=""></option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Widowed</option>
                            <option>Divorced</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Nationality</label>
                        <input type="text" name="nationality" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Contact Number</label>
                        <input type="text" name="contact" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label>Home Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="button" class="btn btn-nav next">Next →</button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: EDUCATIONAL BACKGROUND -->
            <div class="step">
                <div class="row">
                    <h5 class="text-light mb-3">Educational Background</h5>

                    <div class="col-md-6">
                        <label>Elementary School</label>
                        <input type="text" name="elem_school" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Elementary Year Attended</label>
                        <input type="text" name="elem_year" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Secondary School</label>
                        <input type="text" name="sec_school" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Secondary Year Attended</label>
                        <input type="text" name="sec_year" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>College</label>
                        <input type="text" name="college_school" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>College Year Attended</label>
                        <input type="text" name="college_year" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Current Course Enrolled</label>
                        <input type="text" name="course" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Year Level</label>
                        <select name="year_level" class="form-control" required>
                            <option value=""></option>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="button" class="btn btn-nav prev">← Back</button>
                        <button type="button" class="btn btn-nav next">Next →</button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: FAMILY BACKGROUND -->
            <div class="step">
                <div class="row">
                    <h5 class="text-light mb-3">Family Background</h5>

                    <div class="col-md-4">
                        <label>Father's Name</label>
                        <input type="text" name="father_name" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Age</label>
                        <input type="number" name="father_age" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Occupation</label>
                        <input type="text" name="father_occupation" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Contact No.</label>
                        <input type="text" name="father_contact" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Mother's Name</label>
                        <input type="text" name="mother_name" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Age</label>
                        <input type="number" name="mother_age" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Occupation</label>
                        <input type="text" name="mother_occupation" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Contact No.</label>
                        <input type="text" name="mother_contact" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Guardian (if any)</label>
                        <input type="text" name="guardian_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Guardian Contact</label>
                        <input type="text" name="guardian_contact" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Relationship</label>
                        <input type="text" name="guardian_relation" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Parent's Monthly Income</label>
                        <input type="text" name="parent_income" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>No. of Siblings</label>
                        <input type="number" name="siblings" class="form-control" required>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="button" class="btn btn-nav prev">← Back</button>
                        <button type="button" class="btn btn-nav next">Next →</button>
                    </div>
                </div>
            </div>

            <!-- STEP 4: MEDICAL HISTORY -->
            <div class="step">
                <div class="row">
                    <h5 class="text-light mb-3">Medical History</h5>

                    <div class="col-md-6">
                        <label>Height (cm)</label>
                        <input type="text" name="height" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Weight (kg)</label>
                        <input type="text" name="weight" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Blood Type</label>
                        <input type="text" name="blood_type" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Known Allergies</label>
                        <input type="text" name="allergies" class="form-control" placeholder="e.g. Food, Drugs, Others" required>
                    </div>

                    <div class="col-md-12">
                        <label>Pre-existing Conditions / Chronic Illness</label>
                        <input type="text" name="conditions" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label>History of Serious Illness or Surgery</label>
                        <input type="text" name="illness_history" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label>Current Medication</label>
                        <input type="text" name="current_medication" class="form-control" required>
                    </div>

                    <!-- Communicable Diseases -->
                    <div class="col-md-6 mb-3">
                        <label>Communicable Diseases (e.g., TB, Hepatitis)</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="communicable" id="commNone" value="None" required>
                            <label class="form-check-label" for="commNone">None</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="communicable" id="commYes" value="Yes">
                            <label class="form-check-label" for="commYes">Yes</label>
                        </div>

                        <div id="commDetails" class="mt-3 d-none">
                            <div class="mb-2">
                                <label class="form-label">Name of Disease</label>
                                <input type="text" name="communicable_name" id="communicable_name" class="form-control" placeholder="e.g. Tuberculosis">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Medication / Treatment</label>
                                <input type="text" name="communicable_medication" id="communicable_medication" class="form-control" placeholder="e.g. Isoniazid, DOTS">
                            </div>
                        </div>
                    </div>

                    <!-- Mental Health History -->
                    <div class="col-md-6 mb-3">
                        <label>Mental Health History</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mental_health" id="healthNone" value="None" required>
                            <label class="form-check-label" for="healthNone">None</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mental_health" id="healthYes" value="Yes">
                            <label class="form-check-label" for="healthYes">Yes</label>
                        </div>

                        <div id="healthDetails" class="mt-3 d-none">
                            <div class="mb-2">
                                <label>Name of Disease</label>
                                <input type="text" name="mental_health_name" id="mental_health_name" class="form-control" placeholder="e.g. Tuberculosis">
                            </div>
                            <div class="mb-2">
                                <label>Medication / Treatment</label>
                                <input type="text" name="mental_health_medication" id="mental_health_medication" class="form-control" placeholder="e.g. Isoniazid, DOTS">
                            </div>
                        </div>
                    </div>

                    <!-- Any Physical Disability -->
                    <div class="col-md-6 mb-3">
                        <label>Any Physical Disability</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="physical" id="physicalNone" value="None" required>
                            <label class="form-check-label" for="physicalNone">None</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="physical" id="physicalYes" value="Yes">
                            <label class="form-check-label" for="physicalYes">Yes</label>
                        </div>

                        <div id="physicalDetails" class="mt-3 d-none">
                            <div class="mb-2">
                                <label class="form-label">Name of Disease</label>
                                <input type="text" name="physical_name" id="physical_name" class="form-control" placeholder="e.g. Tuberculosis">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Medication / Treatment</label>
                                <input type="text" name="physical_medication" id="physical_medication" class="form-control" placeholder="e.g. Isoniazid, DOTS">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Medical Check-up (Date and Purpose)</label>
                        <input type="text" name="last_med_checkup" class="form-control">
                    </div>

                    <!-- Menstrual Cycle -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Menstrual Cycle:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menstrual_cycle" id="cycleRegular" value="Regular" required>
                            <label class="form-check-label" for="cycleRegular">Regular</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menstrual_cycle" id="cycleIrregular" value="Irregular">
                            <label class="form-check-label" for="cycleIrregular">Irregular</label>
                        </div>
                    </div>

                    <!-- Existing Reproductive Health Issue -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Is there existing reproductive health issue?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="reproductive_issue" id="issueNone" value="None" required>
                            <label class="form-check-label" for="issueNone">None</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="reproductive_issue" id="issueYes" value="Yes">
                            <label class="form-check-label" for="issueYes">Yes</label>
                        </div>

                        <div id="issueDetails" class="mt-3 d-none">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Specify</label>
                                    <input type="text" name="reproductive_specify" id="reproductive_specify" class="form-control" placeholder="e.g., PCOS, Endometriosis">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Medication</label>
                                    <input type="text" name="reproductive_medication" id="reproductive_medication" class="form-control" placeholder="e.g., Metformin, Hormone therapy">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pregnancy -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Is the applicant currently pregnant?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pregnant" id="pregnantNo" value="No" required>
                            <label class="form-check-label" for="pregnantNo">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pregnant" id="pregnantYes" value="Yes">
                            <label class="form-check-label" for="pregnantYes">Yes</label>
                        </div>

                        <div id="pregnancyDetails" class="mt-3 d-none">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Last Medical Check-up</label>
                                    <input type="date" name="last_checkup" id="last_checkup" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Name of Physician</label>
                                    <input type="text" name="physician_name" id="physician_name" class="form-control" placeholder="Dr. Jane Doe">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="physician_contact" id="physician_contact" class="form-control" placeholder="e.g., 0917XXXXXXX">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 text-end">
                        <button type="button" class="btn btn-nav prev">← Back</button>
                        <button type="button" class="btn btn-nav next">Next →</button>
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="row">
                    <h5 class="text-light mb-3">Upload Profile Picture</h5>

                    <!-- Clickable preview circle -->
                    <div class="col-md-12 mt-3 text-center">
                        <div id="profileContainer"
                            style="width: 200px; height: 200px; border-radius: 50%; border: 2px solid var(--gold); background-color: #fff; display:flex; align-items:center; justify-content:center; margin: 0 auto; cursor: pointer; overflow: hidden;">
                            <span id="placeholderText" style="color: #aaa; font-size: 16px; text-align:center;">Click to select</span>
                            <img id="profilePreview" src="" alt="Profile Picture Preview"
                                style="width:100%; height:100%; object-fit:cover; display:none;">
                        </div>
                    </div>

                    <!-- Hidden file input -->
                    <input type="file" name="profile_picture" class="form-control" accept="image/*" required id="profilePictureInput" style="display:none;" required>

                    <!-- Navigation buttons -->
                    <div class="col-md-12 mt-3 text-end">
                        <button type="button" class="btn btn-nav prev">← Back</button>
                        <button type="button" class="btn btn-nav next">Next →</button>
                    </div>
                </div>
            </div>

            <script>
                const input = document.getElementById('profilePictureInput');
                const preview = document.getElementById('profilePreview');
                const placeholder = document.getElementById('placeholderText');
                const container = document.getElementById('profileContainer');

                // Click on the circle opens file picker
                container.addEventListener('click', () => input.click());

                // When file is selected
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            preview.src = event.target.result;
                            preview.style.display = 'block';
                            placeholder.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.style.display = 'none';
                        placeholder.style.display = 'block';
                    }
                });
            </script>

            <!-- STEP 5: CONFIRMATION -->
            <div class="step">
                <div class="row">
                    <h5 class="text-light mb-3">Data Privacy Statement</h5>

                    <div class="col-md-12 text-light mb-3">
                        <p>In compliance with Republic Act No. 10173, also known as the Data Privacy Act of 2012
                            the Dormitory Management ensures that your personal and sensitive information will be collected,
                            processed, stored, and safeguarded in accordiance with the law. By submitting this form,
                            you understand and agree to the following:</p>
                        <ul>
                            <li>The information collected will be used solely for dormitory accomodation management,
                                security, and emergency purposes.
                            </li>
                            <li>Your data will be accessed only bu authorized personnel and will not be shared with third parties
                                without your explicit consent unless required by law.
                            </li>
                            <li>You have the right to access, correct, or request deletion of your personal information, Subject
                                to reasonable limitations.
                            </li>
                            <li>Dormitory administrators are committed to implementing physical, technical, and organizational measures to protect
                                your data against loss, unauthorized access, or misuse.
                            </li>
                        </ul>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="confirmCheck" required>
                            <label class="form-check-label" for="confirmCheck">
                                I hereby certify that the information I have provided is complete and accurate.
                            </label>
                        </div>
                    </div>


                    <h5 class="text-light mb-3">Rules and Regulations</h5>

                    <div class="col-md-12 text-light mb-3">
                        <ol>
                            <li>No loitering in other rooms.</li>
                            <li>No one should be eating in the kitchen after 9 PM (Except dagitay adda duty na po ah working students)</li>
                            <li>Clean the table after eating.</li>
                            <li>Dispose your own garbage.</li>
                            <li>Remove your bed curtains when leaving (kung may kurtina sa bed).</li>
                            <li>Meats should be in Tupperware.</li>
                            <li>Always clean your respective areas.</li>
                            <li>Minimize voise around especially around 10:00 PM onward</li>
                            <li>Washing Plates in Cr is not allowed (if adda makita u report agad)</li>
                            <li>Sweeping your trash into another room is not allowed( if adda makita u report agad)</li>
                            <li>Keep your Rooms Clean</li>
                            <li>Every rooms must have cleaning materials</li>
                            <li>Proper Hygiene</li>
                            <li>Electric Instant Cooker is not allowed</li>
                            <li>The Fire exit is not a tambayan area. Dorm from the fire exit should be locked before 9: 30pm</li>
                            <li>No residents is allowed to enter the other rooms without the permission of the room members.</li>
                            <li>Taking a bath together are strictly not allowed, unless pinanganak kayong magkadikit.</li>
                            <li>WAG MAGNAKAW</li>
                        </ol>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="confirmRules" required>
                            <label class="form-check-label" for="confirmRules">
                                I read and understood the above rules and regulations, and I agree to abide by them during my stay in the dormitory. I acknowledge that failure to comply with these rules may result in disciplinary action, including possible eviction from the dormitory.
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="button" class="btn btn-nav prev">← Back</button>
                        <button type="submit" name="submit" class="btn btn-apply px-4 py-2">Submit Application</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none text-light">← Back to Home</a>
        </div>
    </div>
    <script>
        (function() {
            const issueYes = document.getElementById('issueYes');
            const issueNone = document.getElementById('issueNone');
            const issueDetails = document.getElementById('issueDetails');
            const issueInputs = issueDetails.querySelectorAll('input');

            const pregYes = document.getElementById('pregnantYes');
            const pregNo = document.getElementById('pregnantNo');
            const pregDetails = document.getElementById('pregnancyDetails');
            const pregInputs = pregDetails.querySelectorAll('input');

            function toggleSection(radioYes, radioNo, section, inputs) {
                if (radioYes.checked) {
                    section.classList.remove('d-none');
                    inputs.forEach(i => i.required = true);
                } else {
                    section.classList.add('d-none');
                    inputs.forEach(i => {
                        i.required = false;
                        i.value = '';
                    });
                }
            }

            function updateAll() {
                toggleSection(issueYes, issueNone, issueDetails, issueInputs);
                toggleSection(pregYes, pregNo, pregDetails, pregInputs);
            }

            document.querySelectorAll('input[name="reproductive_issue"], input[name="pregnant"]').forEach(r => {
                r.addEventListener('change', updateAll);
            });

            updateAll(); // initialize
        })();
    </script>

    <script>
        (function() {
            const noneRadio = document.getElementById('commNone');
            const yesRadio = document.getElementById('commYes');
            const details = document.getElementById('commDetails');
            const nameInput = document.getElementById('communicable_name');
            const medInput = document.getElementById('communicable_medication');

            function updateVisibility() {
                if (yesRadio.checked) {
                    details.classList.remove('d-none');
                    nameInput.required = true;
                    medInput.required = true;
                } else {
                    details.classList.add('d-none');
                    nameInput.required = false;
                    medInput.required = false;
                    nameInput.value = '';
                    medInput.value = '';
                }
            }

            // initial state (in case a value is pre-filled)
            updateVisibility();

            noneRadio.addEventListener('change', updateVisibility);
            yesRadio.addEventListener('change', updateVisibility);
        })();
        (function() {
            const noneRadio = document.getElementById('healthNone');
            const yesRadio = document.getElementById('healthYes');
            const details = document.getElementById('healthDetails');
            const nameInput = document.getElementById('mental_health_name');
            const medInput = document.getElementById('mental_health_medication');

            function updateVisibility() {
                if (yesRadio.checked) {
                    details.classList.remove('d-none');
                    nameInput.required = true;
                    medInput.required = true;
                } else {
                    details.classList.add('d-none');
                    nameInput.required = false;
                    medInput.required = false;
                    nameInput.value = '';
                    medInput.value = '';
                }
            }

            // initial state (in case a value is pre-filled)
            updateVisibility();

            noneRadio.addEventListener('change', updateVisibility);
            yesRadio.addEventListener('change', updateVisibility);
        })();
        (function() {
            const noneRadio = document.getElementById('physicalNone');
            const yesRadio = document.getElementById('physicalYes');
            const details = document.getElementById('physicalDetails');
            const nameInput = document.getElementById('physical_name');
            const medInput = document.getElementById('physical_medication');

            function updateVisibility() {
                if (yesRadio.checked) {
                    details.classList.remove('d-none');
                    nameInput.required = true;
                    medInput.required = true;
                } else {
                    details.classList.add('d-none');
                    nameInput.required = false;
                    medInput.required = false;
                    nameInput.value = '';
                    medInput.value = '';
                }
            }

            // initial state (in case a value is pre-filled)
            updateVisibility();

            noneRadio.addEventListener('change', updateVisibility);
            yesRadio.addEventListener('change', updateVisibility);
        })();
    </script>
    <!-- SWEETALERT HANDLER -->
    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Application Submitted!',
                text: <?= json_encode($success) ?>,
                confirmButtonColor: '#e3b23c'
            }).then(() => {
                // clean URL so it won't reappear on refresh
                window.history.replaceState({}, document.title, "apply.php");
            });
        </script>
    <?php endif; ?>

    <?php if ($error): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: <?= json_encode($error) ?>,
                confirmButtonColor: '#7a1e1e'
            }).then(() => {
                window.history.replaceState({}, document.title, "apply.php");
            });
        </script>
    <?php endif; ?>
    <script>
        const steps = document.querySelectorAll(".step");
        const nextBtns = document.querySelectorAll(".next");
        const prevBtns = document.querySelectorAll(".prev");
        let currentStep = 0;

        // Function to validate all required fields in a given step
        function validateStep(step) {
            const requiredFields = step.querySelectorAll('[required]');
            let missing = [];

            requiredFields.forEach(field => {
                // Handle checkboxes and radio buttons
                if ((field.type === 'checkbox' || field.type === 'radio')) {
                    const group = step.querySelectorAll(`[name="${field.name}"]`);
                    const checked = Array.from(group).some(r => r.checked);
                    if (!checked) {
                        const label = step.querySelector(`label[for="${field.id}"]`);
                        const name = label ? label.innerText : field.name;
                        missing.push(name);
                    }
                } else {
                    if (!field.value.trim()) {
                        const label = step.querySelector(`label[for="${field.id}"]`);
                        const name = label ? label.innerText : field.name;
                        missing.push(name);
                    }
                }
            });

            return missing;
        }

        // Next button click
        nextBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                const step = steps[currentStep];
                const missing = validateStep(step);

                if (missing.length > 0) {
                    // Show SweetAlert for missing fields
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Step',
                        html: 'Please fill out the following required fields:<br><b>' + missing.join('<br>') + '</b><br> Type N/A if not applicable.',
                        confirmButtonColor: '#e3b23c'
                    });
                    return; // don't go to next step
                }

                // Proceed to next step
                steps[currentStep].classList.remove("active");
                currentStep++;
                steps[currentStep].classList.add("active");
            });
        });

        // Prev button click
        prevBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                steps[currentStep].classList.remove("active");
                currentStep--;
                steps[currentStep].classList.add("active");
            });
        });
    </script>
</body>

</html>