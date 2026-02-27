<?php
$pageTitle = "View Check-Out Form";
include 'includes/header.php';
include '../config/conn.php';

$contract_id = $_GET['id'] ?? 0;
if (!$contract_id) exit("Invalid contract ID.");

// Fetch check-out info + student full name
$checkout_q = mysqli_query($conn, "
    SELECT ch.*, p.full_name
    FROM checkouts ch
    INNER JOIN user_personal_information p ON ch.student_id = p.student_id
    WHERE ch.contract_id = '$contract_id'
");
$checkout = mysqli_fetch_assoc($checkout_q);
if (!$checkout) exit("Check-Out information not found.");
?>
<style>
    /* Print-specific styles */
    @media print {
        body * {
            visibility: hidden;
            /* hide everything by default */
        }

        .print-area,
        .print-area * {
            visibility: visible;
            /* show only this container */
        }

        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Optional: remove shadows and colors for print clarity */
        .print-area .card {
            box-shadow: none;
            border: 1px solid #000;
        }

        .print-area button {
            display: none;
        }
    }
</style>
<div class="container py-5 min-vh-100">
    <div class="d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow-sm col-8 print-area">
            <!-- Contract Header Image -->
            <div class="contract-header mb-3 text-center">
                <img src="../assets/img/contract_header.png" alt="Contract Header" style="width:100%; max-height:140px; object-fit:contain;">
            </div>

            <h4 class="text-center mb-4">LADIES DORMITORY RESIDENT CHECK-OUT FORM</h4>

            <!-- Resident Info -->
            <h6 class="fw-bold">Resident Information</h6>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($checkout['full_name']) ?></p>
            <p><strong>Room/Unit Number:</strong> <?= htmlspecialchars($checkout['checkout_room']) ?></p>
            <p><strong>Permanent Home Address:</strong> <?= htmlspecialchars($checkout['home_address']) ?></p>

            <h6 class="fw-bold mt-4">Reason for Check-Out</h6>
            <p><?= htmlspecialchars($checkout['reason']) ?></p>

            <h6 class="fw-bold mt-4">Check-Out Details</h6>
            <p><strong>Official Check-Out Date:</strong> <?= htmlspecialchars($checkout['checkout_date']) ?></p>
            <p><strong>Official Check-Out Time:</strong> <?= htmlspecialchars($checkout['checkout_time']) ?></p>
            <p><strong>New Contact Number:</strong> <?= htmlspecialchars($checkout['new_contact']) ?></p>

            <h6 class="fw-bold mt-4">Financial Clearance</h6>
            <p><strong>Outstanding Fees:</strong> ₱<?= number_format($checkout['outstanding_fees'], 2) ?></p>
            <p><strong>Damage Charges:</strong> ₱<?= number_format($checkout['damage_charges'], 2) ?></p>
            <p><strong>Total Due:</strong> ₱<?= number_format($checkout['total_due'], 2) ?></p>

            <h6 class="fw-bold mt-4">Approval</h6>
            <p class="small text-muted mb-1">
                This check-out form has been reviewed and approved using <strong>iDORM System</strong>.
            </p>
            <p><strong>Approved By:</strong> <?= htmlspecialchars($checkout['approved_by']) ?></p>

            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="window.print()">Print Check-Out Form</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>