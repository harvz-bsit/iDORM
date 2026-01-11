<?php
include '../config/conn.php';
$student_id = $_SESSION['student_id'];

// Fetch student info
$query = "SELECT full_name, address FROM user_personal_information WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Contract | IDORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body {
            background: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .contract-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        canvas {
            border: 2px solid #333;
            border-radius: 10px;
            width: 100%;
            height: 200px;
        }

        .signature-box {
            border: 2px dashed #999;
            border-radius: 12px;
            background: #fafafa;
            width: 100%;
            max-width: 500px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
        }

        .signature-box canvas {
            width: 100%;
            height: 100%;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <form id="contractForm" method="POST" action="../includes/processes.php" class="mt-3">
        <div class="contract-container">
            <h3 class="text-center fw-bold mb-4">Accommodation and Housing Contract</h3>

            <p>This Accommodation and Housing Contract is executed this <b><?php echo date('Y-m-d'); ?></b> by and between:</p>

            <p><b>Cristine Joy O. Pera</b> (Dormitory Manager), of legal age, Filipino citizen and a resident of <b>Pula, Tagudin, Ilocos Sur</b>,</p>
            <p>Acting on her capacity as the Dormitory Manager of Ladies' Dormitory of Ilocos Sur Polytechnic State College (ISPSC) Main Campus,
                a Higher Educational Institution located at San Nicolas, Candon City, Ilocos Sur.
            </p>
            <p class="text-center">-and-</p>
            <p><b><?php echo $row['full_name'] ?></b> of legal age, officially enrolled student of the College and a resident of <b><?php echo $row['address'] ?></b></p>

            <p class="text-center fw-bold">GENERAL TERMS AND CONDITION</p>
            <ol>
                <li><b>Property.</b> The leased dwelling is located inside the premises of Ilocos Sur Polytechnic State College Main Campus, San Nicolas, Candon Cit, and hereby known
                    as the Ladies' Dormitory.
                </li>
                <li>
                    <span class="d-flex align-items-center flex-wrap gap-2">
                        <b>Term.</b> This contract will be for the
                        <select class="form-select-sm w-25" id="sem" name="sem" required>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="First">First</option>
                            <option value="Second">Second</option>
                            <option value="Midyear">Midyear</option>
                        </select> Term of School Year
                        <input type="text"
                            class="form-control-sm w-25"
                            name="school_year"
                            placeholder="20XX-20XX"
                            pattern="20\d{2}-20\d{2}"
                            title="Enter a school year in the format 20XX-20XX"
                            required>,
                        beginning in the month of
                        <select class="form-select-sm w-25" id="start_month" name="start_month" required>
                            <option value="" disabled selected>Select Month</option>
                            <option value="January <?= date('Y') ?>">January <?= date('Y') ?></option>
                            <option value="February <?= date('Y') ?>">February <?= date('Y') ?></option>
                            <option value="March <?= date('Y') ?>">March <?= date('Y') ?></option>
                            <option value="April <?= date('Y') ?>">April <?= date('Y') ?></option>
                            <option value="May <?= date('Y') ?>">May <?= date('Y') ?></option>
                            <option value="June <?= date('Y') ?>">June <?= date('Y') ?></option>
                            <option value="July <?= date('Y') ?>">July <?= date('Y') ?></option>
                            <option value="August <?= date('Y') ?>">August <?= date('Y') ?></option>
                            <option value="September <?= date('Y') ?>">September <?= date('Y') ?></option>
                            <option value="October <?= date('Y') ?>">October <?= date('Y') ?></option>
                            <option value="November <?= date('Y') ?>">November <?= date('Y') ?></option>
                            <option value="December <?= date('Y') ?>">December <?= date('Y') ?></option>
                        </select>, and ending on
                        <select class="form-select-sm w-25" id="end_month" name="end_month" required>
                            <option value="" disabled selected>Select Month</option>
                            <option value="January <?= date('Y') ?>">January <?= date('Y') ?></option>
                            <option value="February <?= date('Y') ?>">February <?= date('Y') ?></option>
                            <option value="March <?= date('Y') ?>">March <?= date('Y') ?></option>
                            <option value="April <?= date('Y') ?>">April <?= date('Y') ?></option>
                            <option value="May <?= date('Y') ?>">May <?= date('Y') ?></option>
                            <option value="June <?= date('Y') ?>">June <?= date('Y') ?></option>
                            <option value="July <?= date('Y') ?>">July <?= date('Y') ?></option>
                            <option value="August <?= date('Y') ?>">August <?= date('Y') ?></option>
                            <option value="September <?= date('Y') ?>">September <?= date('Y') ?></option>
                            <option value="October <?= date('Y') ?>">October <?= date('Y') ?></option>
                            <option value="November <?= date('Y') ?>">November <?= date('Y') ?></option>
                            <option value="December <?= date('Y') ?>">December <?= date('Y') ?></option>
                        </select>.
                    </span>
                </li>
                <li><b>Admission and Retention.</b> Only officially enrolled student with at least 15 units and within the priority list (scholar, first year students,
                    and those from far flung area student) can be accommodated. However, the students who are graduating can be accommodated as they are exempted to the policy. Failure
                    to check in during the first week of the semester means cancellation of slot.</li>
                <li>
                    <b>Dormitory Fee and Payment.</b>Monthly dormitory fee is <b>750</b>. The dormitory fee can be paid in advance in full for the whole
                    duration of accommodation, an advance payment of dormitory fee shall be made which shall serve as security deposit, and can be offset,
                    or use for payment for the last month of accommodation, provided the student resident is clear from any obligations. Dormitory fee is inclusive of electricity,
                    and water utilities. Payment of dormitory fee shall be made directly at Cashier's Office. Note that dormitory payment is non-transferable and non-refundable and
                    failure to settle financial obligation will not be accepted for the following semester/term.
                </li>
                <li>
                    <b>Amenities.</b> The Ladies' Dormitory is provided with refrigerator, chest freeze, television, water dispenser and wall fan. Each student resident is reponsible for the
                    care and proper use of furniture and fixtures provided therein. Each student resident is only allowed to bring two (2) gadgets/appliances at most. Any prohibited or undeclared
                    gadgets/appliances are sufficient termination of accommodation.
                </li>
                <li>
                    <b>Liability of Student Resident.</b> Each student resident shall be responsible for their own garbage collection, segregation and disposal. Student resident are likewise responsible for the area
                    rented and damages incurred to the property shall be replaced by them.
                </li>
                <li><b>Adherance to College's Dormitory Policies.</b> Each student resident shall strictly observe and adhere to the policies, dormitory rules and regulations,
                    norm of conduct and responsibility of dorm residents and in the Accommodation and Housing Contract Terms and Condition attached below.
                </li>
            </ol>

            <div class="mt-4">
                In compliance with Republic Act No. 10173 (Data Privacy Act of 2012), the Management ensures that: All personal and sensitive information provided by the student shall be kept
                secure and confidential. Information will only be used for legitimate dormitory operations such as billing, security, and emergency response. The student has the right to access,
                correct, or requests the deletion of their personal data.
            </div>


            <input type="checkbox" class="form-control-check mt-3" id="agree" required> I have read and understood the terms and conditions.

            <div class="mt-5 text-center">
                <h5 class="fw-bold mb-3">Student Signature</h5>

                <div class="signature-box mx-auto">
                    <canvas id="signature-pad"></canvas>
                </div>

                <div class="mt-2 d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary" id="clear">Clear</button>
                </div>

                <input type="hidden" name="signature" id="signature">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <button type="submit" class="btn btn-success px-5 mt-2">Submit Contract</button>
            </div>

        </div>

    </form>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        // Adjust canvas size dynamically
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        document.getElementById('clear').addEventListener('click', () => signaturePad.clear());

        document.getElementById('contractForm').addEventListener('submit', e => {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Please provide your signature before submitting.");
            } else {
                document.getElementById('signature').value = signaturePad.toDataURL();
            }
        });
    </script>
</body>

</html>