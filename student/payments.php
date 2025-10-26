<?php
$pageTitle = "Payments";
include 'includes/header.php';
?>

<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Payment Overview</h2>
        <p class="text-muted">Track your dormitory payments and receipts.</p>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <h6 class="text-uppercase text-muted mb-2">Current Month</h6>
                <h4 class="fw-bold text-green">October 2025</h4>
                <span class="badge bg-success px-3 py-2 mt-2">Paid</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <h6 class="text-uppercase text-muted mb-2">Next Due</h6>
                <h4 class="fw-bold text-maroon">November 5, 2025</h4>
                <span class="badge bg-warning text-dark px-3 py-2 mt-2">Upcoming</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <h6 class="text-uppercase text-muted mb-2">Total Balance</h6>
                <h4 class="fw-bold text-gold">₱0.00</h4>
                <p class="text-muted small mb-0">You are fully paid up to date 🎉</p>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold text-maroon mb-3">Payment History</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Month</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date Paid</th>
                            <th scope="col">Status</th>
                            <th scope="col">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>October 2025</td>
                            <td>₱750</td>
                            <td>Oct 5, 2025</td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>
                                <button class="btn btn-outline-gold btn-sm view-receipt-btn" data-bs-toggle="modal" data-bs-target="#receiptModal"
                                    data-month="October 2025"
                                    data-amount="₱750"
                                    data-date="October 5, 2025"
                                    data-receipt="../assets/img/receipt-sample.jpg">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>September 2025</td>
                            <td>₱750</td>
                            <td>Sep 4, 2025</td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>
                                <button class="btn btn-outline-gold btn-sm view-receipt-btn" data-bs-toggle="modal" data-bs-target="#receiptModal"
                                    data-month="September 2025"
                                    data-amount="₱750"
                                    data-date="September 4, 2025"
                                    data-receipt="../assets/img/receipt-sample.jpg">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>August 2025</td>
                            <td>₱750</td>
                            <td>Aug 10, 2025</td>
                            <td><span class="badge bg-danger">Late</span></td>
                            <td>
                                <button class="btn btn-outline-gold btn-sm view-receipt-btn" data-bs-toggle="modal" data-bs-target="#receiptModal"
                                    data-month="August 2025"
                                    data-amount="₱750"
                                    data-date="August 10, 2025"
                                    data-receipt="../assets/img/receipt-sample.jpg">
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    // Dynamically populate modal with receipt data
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

            // Set download link
            downloadReceiptBtn.href = imgPath;
            downloadReceiptBtn.download = `Receipt_${button.dataset.month.replace(' ', '_')}.jpg`;
        });
    });
</script>

<?php include 'includes/footer.php'; ?>