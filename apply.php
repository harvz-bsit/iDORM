<?php
session_start();
if (isset($_SESSION['student_id'])) {
    header("Location: student/dashboard.php");
    exit;
}
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

        <form id="applicationForm" action="includes/processes.php" method="POST">
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
                        <input type="text" name="elem_school" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Elementary Year Attended</label>
                        <input type="text" name="elem_year" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Secondary School</label>
                        <input type="text" name="sec_school" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Secondary Year Attended</label>
                        <input type="text" name="sec_year" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>College</label>
                        <input type="text" name="college_school" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>College Year Attended</label>
                        <input type="text" name="college_year" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Current Course Enrolled</label>
                        <input type="text" name="course" class="form-control">
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
                        <input type="text" name="father_name" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Age</label>
                        <input type="number" name="father_age" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Occupation</label>
                        <input type="text" name="father_occupation" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Contact No.</label>
                        <input type="text" name="father_contact" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Mother's Name</label>
                        <input type="text" name="mother_name" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Age</label>
                        <input type="number" name="mother_age" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Occupation</label>
                        <input type="text" name="mother_occupation" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Contact No.</label>
                        <input type="text" name="mother_contact" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Guardian (if any)</label>
                        <input type="text" name="guardian_name" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Guardian Contact</label>
                        <input type="text" name="guardian_contact" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Relationship</label>
                        <input type="text" name="guardian_relation" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Parent's Monthly Income</label>
                        <input type="text" name="parent_income" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>No. of Siblings</label>
                        <input type="number" name="siblings" class="form-control">
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
                        <input type="text" name="height" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Weight (kg)</label>
                        <input type="text" name="weight" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Blood Type</label>
                        <input type="text" name="blood_type" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Known Allergies</label>
                        <input type="text" name="allergies" class="form-control" placeholder="e.g. Food, Drugs, Others">
                    </div>

                    <div class="col-md-12">
                        <label>Pre-existing Conditions / Chronic Illness</label>
                        <input type="text" name="conditions" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label>History of Serious Illness or Surgery</label>
                        <input type="text" name="illness_history" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label>Current Medication</label>
                        <input type="text" name="current_medication" class="form-control">
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
        const steps = document.querySelectorAll(".step");
        const nextBtns = document.querySelectorAll(".next");
        const prevBtns = document.querySelectorAll(".prev");
        let currentStep = 0;

        nextBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                steps[currentStep].classList.remove("active");
                currentStep++;
                steps[currentStep].classList.add("active");
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                steps[currentStep].classList.remove("active");
                currentStep--;
                steps[currentStep].classList.add("active");
            });
        });
    </script>
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

</body>

</html>