<?php
$pageTitle = "Payments Management";
include 'includes/header.php';
?>

<div class="container py-5 min-vh-100">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Student Payments</h2>
        <p class="text-muted">Review uploaded receipts and manage payments.</p>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle" id="adminPaymentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Month Paid</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date Uploaded</th>
                            <th>Receipt</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT pr.*, s.full_name, s.student_id AS student_id 
                                  FROM payment_receipts pr
                                  JOIN user_personal_information s ON pr.student_id = s.student_id
                                  ORDER BY pr.paid_at DESC";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) == 0) {
                            echo '<tr><td colspan="7" class="text-center text-muted">No payment receipts found.</td></tr>';
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $statusClass = $row['status'] === 'Approved' ? 'bg-success' : ($row['status'] === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark');
                        ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?> (<?php echo $row['student_id']; ?>)</td>
                                    <td><?php echo date("F Y", strtotime($row['month_paid'] . "-01")); ?></td>
                                    <td>₱<?php echo number_format($row['amount'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td><?php echo date("M d, Y", strtotime($row['paid_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-outline-maroon btn-sm view-receipt-btn" data-bs-toggle="modal" data-bs-target="#viewReceiptModal"
                                            data-student="<?php echo $row['full_name']; ?>"
                                            data-month="<?php echo date('F Y', strtotime($row['month_paid'] . '-01')); ?>"
                                            data-amount="₱<?php echo number_format($row['amount'], 2); ?>"
                                            data-date="<?php echo date('M d, Y', strtotime($row['paid_at'])); ?>"
                                            data-receipt="<?php echo $row['receipt_path']; ?>">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'Pending') { ?>
                                            <button class="btn btn-success btn-sm me-1 approve-btn" data-id="<?php echo $row['id']; ?>">Approve</button>
                                            <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $row['id']; ?>">Reject</button>
                                        <?php } else { ?>
                                            <span class="text-muted">No actions</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== View Receipt Modal ===== -->
<div class="modal fade" id="viewReceiptModal" tabindex="-1" aria-labelledby="viewReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="viewReceiptModalLabel">Payment Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h5 class="fw-bold text-maroon mb-2" id="receiptStudent"></h5>
                <p class="mb-1">Month: <span id="receiptMonth"></span></p>
                <p class="mb-1">Amount: <span id="receiptAmount"></span></p>
                <p class="text-muted">Date Uploaded: <span id="receiptDate"></span></p>

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
    // Populate receipt modal
    const viewButtons = document.querySelectorAll('.view-receipt-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('receiptStudent').textContent = btn.dataset.student;
            document.getElementById('receiptMonth').textContent = btn.dataset.month;
            document.getElementById('receiptAmount').textContent = btn.dataset.amount;
            document.getElementById('receiptDate').textContent = btn.dataset.date;
            document.getElementById('receiptImage').src = btn.dataset.receipt;
            document.getElementById('downloadReceiptBtn').href = btn.dataset.receipt;
        });
    });

    // Approve/Reject buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'Approved'));
    });
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'Rejected'));
    });

    function updateStatus(receiptId, status) {
        fetch('../includes/processes.php?action=update_receipt_status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: receiptId,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') location.reload();
                else alert(data.message);
            });
    }
</script>

<?php include 'includes/footer.php'; ?>