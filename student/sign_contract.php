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
                    <input type="text" class="form-control form-control-sm w-25" placeholder="Semester"> Term of School Year <b><?php echo date('Y') ?>-<?php echo date('Y') + 1 ?></b> beginning
                    in the month of <b><?php echo date('M m, Y') ?></b> and ending on
                    <input type="text" class="form-control form-control-sm w-25" placeholder="Month">.
                </span>
            </li>
            <li><b>Admission and Retention.</b> Only officially enrolled student with at least 15 units and within the priority list (scholar, first year students,
                and those from far flung area student) can be accommodated. However, the students who are graduating can be accommodated as they are exempted to the policy. Failure
                to check in during the first week of the semester means cancellation of slot.</li>

        </ol>

        <div class="mt-4">
            <input type="checkbox" id="agree" required> I have read and understood the terms and conditions.
        </div>

        <div class="mt-5 text-center">
            <h5 class="fw-bold mb-3">Student Signature</h5>

            <div class="signature-box mx-auto">
                <canvas id="signature-pad"></canvas>
            </div>

            <div class="mt-2 d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-outline-secondary" id="clear">Clear</button>
            </div>

            <form id="contractForm" method="POST" action="save_signature.php" class="mt-3">
                <input type="hidden" name="signature" id="signature">
                <button type="submit" class="btn btn-success px-5">Submit Contract</button>
            </form>
        </div>

    </div>

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