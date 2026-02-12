<?php
$pageTitle = "Payments";
include 'includes/header.php';

// Fetch student ID
$student_id = $_SESSION['student_id'] ?? 0;

// Check application approval
$check_approval = "SELECT status FROM application_approvals WHERE student_id = '$student_id' ORDER BY id DESC LIMIT 1";
$approval_result = mysqli_query($conn, $check_approval);
$approval_row = mysqli_fetch_assoc($approval_result);

?>

<div class="container py-5 min-vh-100">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Payment Overview</h2>
        <p class="text-muted">Track your dormitory payments and receipts.</p>
    </div>

    <?php if ($approval_row && $approval_row['status'] === 'Approved'): ?>
        <?php
        // Get latest approved receipt
        $latestReceiptQuery = "SELECT * FROM payment_receipts 
                               WHERE student_id = '$student_id' AND status='Approved'
                               ORDER BY month_paid DESC 
                               LIMIT 1";
        $latestReceiptResult = mysqli_query($conn, $latestReceiptQuery);
        $latestReceipt = mysqli_fetch_assoc($latestReceiptResult);

        if ($latestReceipt) {
            $lastPaidDate = new DateTime($latestReceipt['month_paid']);
            $nextDueDate = $lastPaidDate->modify('+1 month')->format('F Y');
            $nextDueBadge = 'Upcoming';
        } else {
            $nextDueDate = 'Payment not recorded';
            $nextDueBadge = 'Pending';
        }

        // Fetch all receipts
        $allReceiptsQuery = "SELECT * FROM payment_receipts WHERE student_id='$student_id' ORDER BY paid_at DESC";
        $allReceiptsResult = mysqli_query($conn, $allReceiptsQuery);
        ?>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center p-4">
                    <h6 class="text-uppercase text-muted mb-2">Last Paid</h6>
                    <h4 class="fw-bold text-green">
                        <?php echo $latestReceipt ? date('F Y', strtotime($latestReceipt['month_paid'])) : 'N/A'; ?>
                    </h4>
                    <span class="badge 
                        <?php echo $latestReceipt ? 'bg-success' : 'bg-warning text-dark'; ?>">
                        <?php echo $latestReceipt ? 'Paid' : 'Pending'; ?> (<?php echo $latestReceipt ? date('F j, Y', strtotime($latestReceipt['paid_at'])) : 'N/A'; ?>)
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center p-4">
                    <h6 class="text-uppercase text-muted mb-2">Next Due</h6>
                    <h4 class="fw-bold text-maroon"><?php echo $nextDueDate; ?></h4>
                    <span class="badge bg-warning text-dark"><?php echo $nextDueBadge; ?></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center p-4">
                    <h6 class="text-uppercase text-muted mb-2">Total Paid</h6>
                    <?php
                    $totalQuery = "SELECT SUM(amount) as total_paid FROM payment_receipts WHERE student_id='$student_id'";
                    $totalResult = mysqli_query($conn, $totalQuery);
                    $totalRow = mysqli_fetch_assoc($totalResult);
                    $totalPaid = $totalRow['total_paid'] ?? 0;
                    ?>
                    <h4 class="fw-bold text-gold">₱<?php echo number_format($totalPaid, 2); ?></h4>
                    <span class="badge bg-danger text-light"><?php echo $totalPaid > 0 ? 'Paid' : 'Pending'; ?></span>
                </div>
            </div>
        </div>
        <div class="text-end mb-4">
            <button class="btn btn-gold px-4 py-2" data-bs-toggle="modal" data-bs-target="#uploadReceiptModal">
                <i class="bi bi-upload me-1"></i> Upload Receipt
            </button>
        </div>
        <!-- Payment History Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold text-maroon mb-3">Payment History</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Month Paid</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($allReceiptsResult) > 0): ?>
                                <?php while ($receipt = mysqli_fetch_assoc($allReceiptsResult)): ?>
                                    <tr>
                                        <td><?php echo date('F Y', strtotime($receipt['month_paid'])); ?></td>
                                        <td>₱<?php echo number_format($receipt['amount'], 2); ?></td>
                                        <td><?php echo date('F j, Y', strtotime($receipt['paid_at'])); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php
                                                echo $receipt['status'] === 'Approved' ? 'bg-success' : ($receipt['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-danger');
                                                ?>">
                                                <?php echo $receipt['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-gold btn-sm view-receipt-btn"
                                                data-bs-toggle="modal" data-bs-target="#receiptModal"
                                                data-month="<?php echo date('F Y', strtotime($receipt['paid_at'])); ?>"
                                                data-amount="₱<?php echo number_format($receipt['amount'], 2); ?>"
                                                data-date="<?php echo date('F j, Y', strtotime($receipt['paid_at'])); ?>"
                                                data-receipt="<?php echo $receipt['receipt_path']; ?>">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No receipts uploaded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Your application is not approved yet. Payment details will be available once approved.
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="uploadReceiptModal" tabindex="-1" aria-labelledby="uploadReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="uploadReceiptModalLabel">Upload Payment Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadReceiptForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Month Paid</label>
                        <input type="month" class="form-control" name="month_paid" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="₱" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Receipt Image</label>
                        <input type="file" class="form-control" name="receipt_file" accept="image/*" required>
                    </div>
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <div id="uploadReceiptAlert" class="alert d-none mt-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-maroon" id="submitUploadReceiptBtn">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== Receipt Modal ===== -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="receiptModalLabel">Payment Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h5 class="fw-bold text-maroon mb-2" id="receiptMonth"></h5>
                <p class="mb-1">Amount: <span id="receiptAmount" class="fw-semibold"></span></p>
                <p class="text-muted">Date Paid: <span id="receiptDate"></span></p>

                <!-- Receipt Image -->
                <div class="mt-3">
                    <img id="receiptImage" src="" alt="Receipt Image" class="img-fluid rounded shadow" style="max-height: 400px;">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a id="downloadReceiptBtn" href="#" download class="btn btn-outline-gold px-4">
                    <i class="bi bi-download me-1"></i> Download
                </a>
                <button type="button" class="btn btn-gold px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const viewReceiptButtons = document.querySelectorAll('.view-receipt-btn');
    const receiptMonth = document.getElementById('receiptMonth');
    const receiptAmount = document.getElementById('receiptAmount');
    const receiptDate = document.getElementById('receiptDate');
    const receiptImage = document.getElementById('receiptImage');
    const downloadReceiptBtn = document.getElementById('downloadReceiptBtn');

    viewReceiptButtons.forEach(button => {
        button.addEventListener('click', () => {
            const imgPath = button.dataset.receipt;
            receiptMonth.textContent = button.dataset.month;
            receiptAmount.textContent = button.dataset.amount;
            receiptDate.textContent = button.dataset.date;
            receiptImage.src = imgPath;
            downloadReceiptBtn.href = imgPath;
            downloadReceiptBtn.download = `Receipt_${button.dataset.month.replace(' ', '_')}.jpg`;
        });
    });
</script>

<script>
    document.getElementById('submitUploadReceiptBtn').addEventListener('click', function() {
        const form = document.getElementById('uploadReceiptForm');
        const formData = new FormData(form);

        fetch('../includes/processes.php?action=upload_receipt', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertDiv = document.getElementById('uploadReceiptAlert');
                alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');

                if (data.status === 'success') {
                    alertDiv.classList.add('alert', 'alert-success');
                    alertDiv.textContent = data.message;
                    form.reset();
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadReceiptModal'));
                        modal.hide();
                        alertDiv.classList.add('d-none');
                        location.reload(); // Refresh page to show new receipt
                    }, 1500);
                } else {
                    alertDiv.classList.add('alert', 'alert-danger');
                    alertDiv.textContent = data.message;
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error(err));
    });
</script>

<?php include 'includes/footer.php'; ?>