<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

// Check application status
$check_approval = "SELECT status, remarks FROM application_approvals WHERE student_id = '$student_id' ORDER BY id DESC LIMIT 1";
$approval_result = mysqli_query($conn, $check_approval);
$approval_row = mysqli_fetch_assoc($approval_result);

if ($approval_row && $approval_row['status'] === 'Approved') {
    $contract_check = "SELECT status FROM contracts WHERE student_id = '$student_id' LIMIT 1";
    $contract_result = mysqli_query($conn, $contract_check);
    $contract_row = mysqli_fetch_assoc($contract_result);
}
?>

<div class="container py-5">
    <div class="dashboard-header">
        <h1>Welcome back, <span class="text-gold"><?php echo $personalInfoRow['full_name']; ?></span> 👋</h1>
        <p class="lead">Here’s your dormitory overview at ISPSC Main Campus.</p>
        <div class="divider"></div>
    </div>

    <?php
    // ======= CONDITIONAL DISPLAY =======
    if ($approval_row && $approval_row['status'] === 'Approved') {
        if (!$contract_row || $contract_row['status'] !== 'Signed') {
            include 'sign_contract.php';
        } else {
            // ======= SHOW MAIN DASHBOARD =======
    ?>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card dashboard-card text-center p-4 shadow-lg">
                        <h5>🏠 Room</h5>
                        <p class="fw-semibold mb-0">Room 102</p>
                        <small class="text-muted">Ladies Dorm</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card dashboard-card text-center p-4 shadow-lg">
                        <h5>💳 Payment Status</h5>
                        <p class="fw-semibold mb-0"><span class="badge bg-success px-3 py-2">Paid</span></p>
                        <small class="text-muted">(October 2025)</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card dashboard-card text-center p-4 shadow-lg">
                        <h5>📅 Next Due</h5>
                        <p class="fw-semibold mb-0">November 5, 2025</p>
                        <small class="text-muted">Mark your calendar</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="text-center mt-5">
                <h4 class="text-maroon fw-bold mb-3">Quick Actions</h4>
                <a href="payments.php" class="btn btn-gold px-4 py-2 rounded-pill mx-2 mb-2">View Payments</a>
                <a href="requests.php" class="btn btn-outline-maroon px-4 py-2 rounded-pill mx-2 mb-2">Request Passlip</a>
            </div>

            <!-- Announcements -->
            <div class="card shadow-lg border-rounded mt-5 announcements-card mx-auto" style="max-width: 800px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-maroon mb-1">📢 Recent Announcements</h4>
                        <div class="divider"></div>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-white py-3 border-start border-4 border-success">
                            <h6 class="fw-semibold mb-1 text-dark">Dormitory Cleaning</h6>
                            <small class="text-muted">🏠 Scheduled for October 20, 2025</small>
                        </li>
                        <li class="list-group-item bg-white py-3 border-start border-4 border-warning">
                            <h6 class="fw-semibold mb-1 text-dark">Water Maintenance</h6>
                            <small class="text-muted">💧 October 22, 2025 — expect temporary water interruption</small>
                        </li>
                        <li class="list-group-item bg-white py-3 border-start border-4 border-info">
                            <h6 class="fw-semibold mb-1 text-dark">Dorm Night Event</h6>
                            <small class="text-muted">🎉 November 10, 2025 — join the fun!</small>
                        </li>
                    </ul>
                </div>
            </div>
        <?php
        }
    } else if ($approval_row && $approval_row['status'] === 'Pending') {
        ?>
        <div class="col-12">
            <div class="card dashboard-card text-center p-4 shadow-lg">
                <p class="fw-semibold mb-0">Application Pending</p>
                <small class="text-muted"><?php echo $approval_row['remarks']; ?></small>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="col-12">
            <div class="card dashboard-card text-center p-4 shadow-lg">
                <p class="fw-semibold mb-0">Application Rejected</p>
                <small class="text-muted">Please apply again. Thank you!</small>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>