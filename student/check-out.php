<?php
$pageTitle = "Check-Out Form";
include 'includes/header.php';
include '../config/conn.php';

// Assume user is logged in
$studentId = $_SESSION['student_id'] ?? null;

if (!$studentId) {
    header("Location: login.php");
    exit;
}

// Fetch user's contract info
$contractQuery = "
    SELECT c.id AS contract_id,
           c.signed_at,
           u.full_name,
           u.address,
           ra.room_num
    FROM contracts c
    JOIN user_personal_information u ON c.student_id = u.student_id
    LEFT JOIN room_assignments ra ON u.student_id = ra.student_id
    WHERE c.student_id = '$studentId'
    LIMIT 1
";
$contractResult = mysqli_query($conn, $contractQuery);
$contract = mysqli_fetch_assoc($contractResult);

?>
<div class="container py-5 min-vh-100">
    <h1 class="fw-bold text-maroon mb-3">
        <i class="bi bi-box-arrow-right"></i> Check-Out Form
    </h1>
    <p class="text-muted mb-4">Complete this form to request dormitory check-out.</p>

    <?php if (!$contract): ?>
        <div class="alert alert-warning">No active contract found.</div>
    <?php else: ?>
        <div class="card shadow-sm p-4">
            <form method="POST" action="../includes/processes.php">

                <input type="hidden" name="contract_id" value="<?= $contract['contract_id'] ?>">

                <!-- Resident Info -->
                <h5 class="fw-semibold mb-3">Resident Information</h5>
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars(ucwords($contract['full_name'])) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Room/Unit Number</label>
                    <input type="text" name="checkout_room" class="form-control" value="<?= htmlspecialchars($contract['room_num'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Permanent Home Address</label>
                    <textarea name="home_address" class="form-control"><?= htmlspecialchars($contract['address']) ?></textarea>
                </div>

                <!-- Check-Out Details -->
                <h5 class="fw-semibold mb-3 mt-4">Check-Out Details</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label>Official Check-Out Date</label>
                        <input type="date" name="checkout_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Official Check-Out Time</label>
                        <input type="time" name="checkout_time" class="form-control" required>
                    </div>
                </div>

                <!-- Reason -->
                <h6 class="fw-semibold mb-2">Reason for Check-Out</h6>
                <div class="mb-3">
                    <input type="checkbox" name="reason[]" value="End of Semester"> End of Semester<br>
                    <input type="checkbox" name="reason[]" value="Graduation"> Graduation / Completion<br>
                    <input type="checkbox" name="reason[]" value="Transfer"> Transfer Institution<br>
                    <input type="checkbox" name="reason[]" value="Violation"> Violation of Rules<br>
                    <input type="checkbox" name="reason[]" value="Personal"> Personal / Family Reasons<br>
                    <input type="checkbox" name="reason[]" value="Other"> Other
                </div>

                <!-- Financial -->
                <h5 class="fw-semibold mb-3 mt-4">Financial Clearance</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label>Outstanding Fees (PHP)</label>
                        <input type="number" step="0.01" name="outstanding_fees" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Damage Charges (PHP)</label>
                        <input type="number" step="0.01" name="damage_charges" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Total Due (PHP)</label>
                        <input type="number" step="0.01" name="total_due" class="form-control">
                    </div>
                </div>

                <!-- Submit -->
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" name="processCheckout" class="btn btn-primary">
                        Submit Check-Out Request
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>