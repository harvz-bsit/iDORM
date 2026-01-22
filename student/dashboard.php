<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

// Check application status
$check_approval = "SELECT status FROM application_approvals WHERE student_id = '$student_id' ORDER BY id DESC LIMIT 1";
$approval_result = mysqli_query($conn, $check_approval);
$approval_row = mysqli_fetch_assoc($approval_result);

if ($approval_row && $approval_row['status'] === 'Approved') {
    $contract_check = "SELECT status FROM contracts WHERE student_id = '$student_id' LIMIT 1";
    $contract_result = mysqli_query($conn, $contract_check);
    $contract_row = mysqli_fetch_assoc($contract_result);
}

// ================= ROOM INFO =================
$roomQuery = "
    SELECT r.room_number
    FROM room_assignments ra
    JOIN rooms r ON ra.room_num = r.room_number
    WHERE ra.student_id = '$student_id'
    LIMIT 1
";
$roomResult = mysqli_query($conn, $roomQuery);
$room = mysqli_fetch_assoc($roomResult);

// ================= PAYMENT STATUS =================
$paymentQuery = "
    SELECT month_paid, status
    FROM payment_receipts
    WHERE student_id = '$student_id'
    ORDER BY uploaded_at DESC
    LIMIT 1
";
$paymentResult = mysqli_query($conn, $paymentQuery);
$payment = mysqli_fetch_assoc($paymentResult);

// ================= ANNOUNCEMENTS =================
$announcementsQuery = "
    SELECT title, description, date_posted
    FROM announcements
    ORDER BY date_posted DESC
    LIMIT 3
";
$announcementsResult = mysqli_query($conn, $announcementsQuery);

?>

<div class="container py-5 min-vh-100">
    <div class=" dashboard-header">
        <h1>Welcome back, <span class="text-gold"><?php echo $personalInfoRow['full_name']; ?></span> 👋</h1>
        <p class="lead">Here’s your dormitory overview at ISPSC Main Campus.</p>
        <div class="divider"></div>
    </div>

    <?php
    // ======= CONDITIONAL DISPLAY =======
    $check_password = "SELECT password FROM users WHERE student_id = '$student_id' LIMIT 1";
    $password_result = mysqli_query($conn, $check_password);
    $password_row = mysqli_fetch_assoc($password_result);
    if ($password_row && password_verify('password123', $password_row['password'])) {
    ?>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                <strong>Important:</strong> For your account security, please change your default password.
                <a href="profile.php#change_password" class="alert-link">Change Password Now</a>.
            </div>
        </div>
        <?php
    }
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
                        <p class="fw-semibold mb-0">
                            <?= $room ? 'Room ' . $room['room_number'] : 'Not Assigned'; ?>
                        </p>
                        <small class="text-muted">Ladies Dorm</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card dashboard-card text-center p-4 shadow-lg">
                        <h5>💳 Payment Status</h5>
                        <p class="fw-semibold m-0">
                            <?php if ($payment && $payment['status'] === 'Approved'): ?>
                                <span class="badge bg-success px-3 py-2">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-danger px-3 py-2">Unpaid</span>
                            <?php endif; ?>
                        </p>
                        <small class="text-muted m-0">
                            <?= $payment ? date('F Y', strtotime($payment['month_paid'])) : 'No record'; ?>
                        </small>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card dashboard-card text-center p-4 shadow-lg">
                        <h5>📅 Next Due</h5>
                        <p class="fw-semibold mb-0">
                            <?= //+1 
                            $payment ? date_modify(new DateTime($payment['month_paid']), '+1 month')->format('F Y') : '—';
                            ?>
                        </p>
                        <small class="text-muted">Mark your calendar</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="text-center mt-5">
                <h4 class="text-maroon fw-bold mb-3">Quick Actions</h4>
                <a href="payments.php" class="btn btn-gold px-4 py-2 rounded-pill mx-2 mb-2">View Payments</a>
                <button class="btn btn-outline-maroon px-4 py-2 rounded-pill mx-2 mb-2" data-bs-toggle="modal" data-bs-target="#requestPasslipModal">
                    Request Passlip
                </button>
            </div>

            <div class="row g-4 mt-3">
                <!-- Announcements -->
                <div class="col-lg-6">
                    <div class="card shadow-lg border-rounded announcements-card h-100">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-maroon mb-1">📢 Recent Announcements</h4>
                                <div class="divider"></div>
                            </div>

                            <ul class="list-group list-group-flush">
                                <?php
                                if (mysqli_num_rows($announcementsResult) === 0):
                                ?>
                                    <li class="list-group-item bg-white border-start border-4 border-warning py-3">
                                        <h6 class="fw-semibold mb-1 text-dark">No announcements available</h6>
                                        <p class="mb-1 text-muted">There are no recent announcements.</p>
                                    </li>
                                    <?php
                                else:
                                    while ($announcement = mysqli_fetch_assoc($announcementsResult)) : ?>
                                        <li class="list-group-item bg-white border-start border-4 border-warning py-3">
                                            <h6 class="fw-semibold mb-1 text-dark"><?php echo htmlspecialchars($announcement['title']); ?></h6>
                                            <p class="mb-1 text-muted"><?php echo htmlspecialchars($announcement['description']); ?></p>
                                            <small class="text-muted">📅 Posted on <?php echo date('F d, Y', strtotime($announcement['date_posted'])); ?></small>
                                        </li>
                                <?php endwhile;
                                endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-lg h-100">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h4 class="fw-bold text-maroon mb-1">📝 Passlip Requests</h4>
                                <div class="divider"></div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Departure</th>
                                            <th>Return</th>
                                            <th>Destination</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $passlipQuery = "
                            SELECT id, departure_date, return_date, destination, status
                            FROM passlips
                            WHERE student_id = '$student_id'
                            ORDER BY id DESC
                            LIMIT 5
                        ";
                                        $passlipResult = mysqli_query($conn, $passlipQuery);

                                        if (mysqli_num_rows($passlipResult) === 0):
                                        ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No passlip requests yet
                                                </td>
                                            </tr>
                                            <?php else:
                                            while ($p = mysqli_fetch_assoc($passlipResult)):
                                            ?>
                                                <tr>
                                                    <td><?= date('M d, Y', strtotime($p['departure_date'])); ?></td>
                                                    <td><?= date('M d, Y', strtotime($p['return_date'])); ?></td>
                                                    <td><?= htmlspecialchars($p['destination']); ?></td>
                                                    <td>
                                                        <?php if ($p['status'] === 'Pending'): ?>
                                                            <span class="badge bg-warning">Pending</span>
                                                        <?php elseif ($p['status'] === 'Approved'): ?>
                                                            <span class="badge bg-success">Approved</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Rejected</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                        <?php endwhile;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php
        }
    } else if ($approval_row && $approval_row['status'] === 'Pending') {
        ?>
        <div class="col-12">
            <div class="card dashboard-card text-center p-4 shadow-lg">
                <p class="fw-semibold mb-0">Application Pending</p>
                <small class="text-muted">Your application is under review.</small>
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

<!-- ===== Request Passlip Modal ===== -->
<div class="modal fade" id="requestPasslipModal" tabindex="-1" aria-labelledby="requestPasslipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="requestPasslipModalLabel">Request Passlip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="passlipForm">
                    <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id; ?>" required>
                    <h6 class="fw-bold text-maroon mb-2">Departure Information</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date of Departure</label>
                            <input type="date" class="form-control" name="departure_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time of Departure</label>
                            <input type="time" class="form-control" name="departure_time" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Destination</label>
                            <input type="text" class="form-control" name="destination" placeholder="Where are you going?" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Purpose</label>
                            <input type="text" class="form-control" name="purpose" placeholder="Reason for leaving" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-maroon mb-2">Expected Return</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date of Return</label>
                            <input type="date" class="form-control" name="return_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time of Return</label>
                            <input type="time" class="form-control" name="return_time" required>
                        </div>
                    </div>
                </form>

                <div id="passlipAlert" class="alert d-none mt-3"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-maroon" id="submitPasslipBtn">Submit Request</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('submitPasslipBtn').addEventListener('click', function() {
        const form = document.getElementById('passlipForm');
        const formData = new FormData(form);

        fetch('../includes/process_passlip.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertDiv = document.getElementById('passlipAlert');
                alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
                if (data.status === 'success') {
                    alertDiv.classList.add('alert', 'alert-success');
                    alertDiv.textContent = data.message;
                    form.reset();
                    // Optionally close modal after a delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('requestPasslipModal'));
                        modal.hide();
                        alertDiv.classList.add('d-none');
                    }, 1500);
                } else {
                    alertDiv.classList.add('alert', 'alert-danger');
                    alertDiv.textContent = data.message;
                }
            })
            .catch(err => console.error(err));
    });
</script>

<?php include 'includes/footer.php'; ?>