<?php
include '../config/conn.php';
$pageTitle = "Signed Contract";
include 'includes/header.php';

if (!isset($_GET['id'])) {
    die('Invalid contract.');
}

$contract_id = $_GET['id'];

$query = "
    SELECT 
        c.*, 
        p.*,
        u.email
    FROM contracts c
    INNER JOIN user_personal_information p ON c.student_id = p.student_id
    INNER JOIN users u ON c.student_id = u.student_id
    WHERE c.id = '$contract_id'
";

$result = mysqli_query($conn, $query);
$contract = mysqli_fetch_assoc($result);

if (!$contract) {
    die('Contract not found.');
}
?>

<style>
    /* Screen styles: make it look like a document */
    .document-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px 40px;
        border: 1px solid #ccc;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        background: #fff;
        box-sizing: border-box;
        border-radius: 5px;
    }

    /* Contract content */
    .contract-header img {
        width: 100%;
        max-height: 140px;
        object-fit: contain;
    }

    .signature img {
        height: 80px;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    /* Print styles */
    @media print {

        header,
        footer,
        .sidebar,
        .navbar,
        .print-btn,
        .arrow {
            display: none !important;
        }

        .document-container {
            margin: 0;
            padding: 5mm;
            border: none;
            box-shadow: none;
            border-radius: 0;
            width: 100%;
        }

        @page {
            size: Legal portrait;
            margin: 5mm;
        }

        .document-container p {
            font-weight: 100;
            line-height: 1.6;
            color: #000000;
        }
    }
</style>
<div class="container mt-4 right-100">
    <div class="d-flex justify-content-end">
        <button class="print-btn btn btn-primary" onclick="window.print()">🖨️ Print</button>
    </div>
</div>
<div class="document-container">
    <!-- HEADER IMAGE -->
    <div class="contract-header mb-1">
        <img src="../assets/img/contract_header.png" alt="Contract Header">
    </div>

    <h6 class="text-center fw-bold mb-2">ACCOMMODATION AND HOUSING CONTRACT</h6>

    <p class="mb-0">This Accommodation and Housing Contract is executed this <b><?= date('Y-m-d'); ?></b> by and between:</p>

    <p class="m-0"><b>Cristine Joy O. Pera</b> (Dormitory Manager), of legal age, Filipino citizen and a resident of <b>Pula, Tagudin, Ilocos Sur</b>, Acting on her capacity as the Dormitory Manager of Ladies' Dormitory of Ilocos Sur Polytechnic State College (ISPSC) Main Campus,
        a Higher Educational Institution located at San Nicolas, Candon City, Ilocos Sur.</p>

    <p class="text-center m-0"><b>-and-</b></p>

    <p class="m-0"><b><?= htmlspecialchars($contract['full_name']) ?></b> of legal age, officially enrolled student of the College and a resident of <b><?= htmlspecialchars($contract['address']) ?></b></p>

    <h6 class="text-center fw-bold mt-3">GENERAL TERMS AND CONDITIONS</h6>

    <ol>
        <li>
            <b>Property.</b> The leased dwelling is located inside the premises of Ilocos Sur Polytechnic State College Main Campus, San Nicolas, Candon City, and hereby known as the Ladies' Dormitory.
        </li>
        <li>
            <b>Term.</b> This contract will be for the <b><?= htmlspecialchars($contract['semester']) ?></b> Semester of School Year <b><?= htmlspecialchars($contract['school_year']) ?></b>, beginning in the month of <b><?= htmlspecialchars($contract['contract_start']) ?></b> and ending on <b><?= htmlspecialchars(($contract['contract_end'])) ?></b>.
        </li>
        <li>
            <b>Admission and Retention.</b> Only officially enrolled students with at least 15 units and within the priority list (scholar, first year students, and those from far-flung areas) can be accommodated. Graduating students are exempted. Failure to check in during the first week of the semester means cancellation of slot.
        </li>
        <li>
            <b>Dormitory Fee and Payment.</b> Monthly dormitory fee is <b>₱750</b>. Payment can be made in advance, which shall serve as a security deposit and can be offset against the last month of accommodation. Dormitory fee is inclusive of electricity and water utilities. Payment must be made at the Cashier's Office. Fee is non-transferable and non-refundable.
        </li>
        <li>
            <b>Amenities.</b> The Ladies' Dormitory provides refrigerator, chest freezer, television, water dispenser, and wall fan. Each student resident is responsible for proper care of furniture and fixtures. Only two (2) gadgets/appliances are allowed. Undeclared gadgets may result in termination of accommodation.
        </li>
        <li>
            <b>Liability of Student Resident.</b> Each resident is responsible for garbage collection, area upkeep, and any damages incurred.
        </li>
        <li>
            <b>Adherence to College's Dormitory Policies.</b> Residents must follow all dormitory rules, code of conduct, and responsibilities outlined in this contract.
        </li>
    </ol>

    <div class="mt-3">
        <b>Data Privacy Compliance:</b> In compliance with Republic Act No. 10173 (Data Privacy Act of 2012), all personal and sensitive information will be kept secure and confidential. Information will only be used for dormitory operations. The student has the right to access, correct, or request deletion of their personal data.
    </div>

    <!-- SIGNATURE -->
    <div class="signature mt-2 mb-0 text-center">
        <?php if ($contract['signature_path']) : ?>
            <img src="<?= $contract['signature_path'] ?>" alt="Signature" class="img-fluid m-0">
        <?php endif; ?>
        <p class="m-0"><u><strong><?= htmlspecialchars($contract['full_name']) ?></strong></u><br>
            Date Signed: <?= date('F d, Y', strtotime($contract['signed_at'])) ?></p>
    </div>

</div>

<?php include 'includes/footer.php'; ?>