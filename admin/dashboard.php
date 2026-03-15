<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

/* ===============================
   ROOM STATS
================================ */
$roomStats = mysqli_query($conn, "
    SELECT 
        SUM(capacity) AS total_capacity,
        SUM(occupied) AS total_occupied
    FROM rooms
");
$room = mysqli_fetch_assoc($roomStats);

$total_capacity  = $room['total_capacity'] ?? 0;
$total_occupied  = $room['total_occupied'] ?? 0;
$available_slots = $total_capacity - $total_occupied;

/* ===============================
   PENDING APPLICATIONS
================================ */
$pendingQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM application_approvals 
    WHERE status = 'Pending'
");
$pending_approvals = mysqli_fetch_assoc($pendingQuery)['total'] ?? 0;

/* ===============================
   OUTSTANDING PAYMENTS
   (Unverified receipts)
================================ */
$outstandingQuery = mysqli_query($conn, "
    SELECT SUM(amount) AS total 
    FROM payment_receipts 
    WHERE status = 'Pending'
");
$outstanding_amount = $outstandingQuery
    ? (mysqli_fetch_assoc($outstandingQuery)['total'] ?? 0)
    : 0;

/* ===============================
   MONTHLY PAYMENTS (Chart)
================================ */
$monthlyLabels = [];
$monthlyData   = [];

$paymentResult = mysqli_query($conn, "
    SELECT 
        month_paid,
        SUM(amount) AS total
    FROM payment_receipts
    WHERE status = 'Approved'
    GROUP BY month_paid
    ORDER BY STR_TO_DATE(month_paid, '%M %Y') ASC
    LIMIT 6
");

while ($row = mysqli_fetch_assoc($paymentResult)) {
    $monthlyLabels[] = $row['month_paid'];
    $monthlyData[]   = $row['total'];
}
/* ===============================
   ANNOUNCEMENTS
================================ */
$announcements = mysqli_query($conn, "
    SELECT title 
    FROM announcements 
    ORDER BY date_posted DESC 
    LIMIT 3
");

/* ===============================
   APPROVED PASS SLIPS COUNT
================================ */
$passSlipCountQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM passlips
    WHERE status = 'Approved'
");

$approved_pass_slips = mysqli_fetch_assoc($passSlipCountQuery)['total'] ?? 0;


/* ===============================
   APPROVED PASS SLIP LIST
================================ */
$passSlipList = mysqli_query($conn, "
    SELECT 
        p.student_id,
        upi.full_name,
        p.destination,
        p.departure_date,
        p.departure_time
    FROM passlips p
    LEFT JOIN user_personal_information upi
        ON p.student_id = upi.student_id
    WHERE p.status = 'Approved'
    ORDER BY p.departure_date DESC
    LIMIT 5
");

$managerQuery = mysqli_query($conn, "SELECT setting_value FROM system_settings WHERE setting_key='dorm_manager'");
$manager = mysqli_fetch_assoc($managerQuery)['setting_value'] ?? 'Not set';
?>

<div class="container py-5 min-vh-100">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Welcome back, <span class="text-gold">Admin!</span> 👋</h1>
        <p class="lead">Here’s a quick overview of the Dormitory</p>
        <div class="divider"></div>
    </div>

    <!-- ======= Dashboard Cards ======= -->
    <div class="d-flex justify-content-end align-items-center mb-3">
        <span class="px-3 py-1 bg-maroon border rounded-pill text-white shadow-sm">
            Dorm Manager: <span id="managerName" class="fw-semibold"><?= htmlspecialchars($manager) ?></span>
        </span>
        <button class="btn btn-sm btn-outline-secondary ms-2 p-1" style="line-height:1;" data-bs-toggle="modal" data-bs-target="#editManagerModal">
            <i class="bi bi-pencil-fill"></i>
        </button>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <a href="rooms.php" class="text-decoration-none">
                <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                    <i class="bi bi-door-open display-5 text-success mb-2"></i>
                    <h5 class="fw-semibold text-maroon">Available Slots</h5>
                    <h2 class="fw-bold text-dark"><?php echo $available_slots; ?></h2>
                    <p class="text-muted mb-0">Rooms ready for occupancy</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="rooms.php" class="text-decoration-none">
                <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                    <i class="bi bi-house-door display-5 text-primary mb-2"></i>
                    <h5 class="fw-semibold text-maroon">Occupied Rooms</h5>
                    <h2 class="fw-bold text-dark"><?php echo $total_occupied; ?></h2>
                    <p class="text-muted mb-0">Currently assigned</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="applications.php" class="text-decoration-none">
                <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                    <i class="bi bi-person-check display-5 text-warning mb-2"></i>
                    <h5 class="fw-semibold text-maroon">Pending Approvals</h5>
                    <h2 class="fw-bold text-dark"><?php echo $pending_approvals; ?></h2>
                    <p class="text-muted mb-0">Applicants waiting review</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="payments.php" class="text-decoration-none">
                <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                    <i class="bi bi-cash-stack display-5 text-danger mb-2"></i>
                    <h5 class="fw-semibold text-maroon">Outstanding Payments</h5>
                    <h2 class="fw-bold text-dark">₱<?php echo number_format($outstanding_amount, 2); ?></h2>
                    <p class="text-muted mb-0">Unverified receipts</p>
                </div>
            </a>
        </div>
    </div>

    <!-- ======= Approved Pass Slips ======= -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon">
                        <i class="bi bi-box-arrow-right"></i> Students Going Out (Approved Pass Slips)
                    </h5>
                    <span class="badge bg-success">
                        <?php echo $approved_pass_slips; ?> Approved
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Destination</th>
                                <th>Departure Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($passSlipList) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No approved pass slips
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($passSlipList)): ?>
                                    <tr>
                                        <td><?php echo ucfirst($row['student_id']); ?></td>
                                        <td><?php echo ucwords($row['full_name']); ?></td>
                                        <td><?php echo ucwords($row['destination']); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($row['departure_date'])); ?></td>
                                        <td><?php echo date("h:i A", strtotime($row['departure_time'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Charts Section ======= -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="dashboard-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon">
                        <i class="bi bi-building"></i> Room Occupancy Overview
                    </h5>
                    <a href="rooms.php" class="text-gold small text-decoration-none">View details →</a>
                </div>
                <canvas id="roomChart" height="180"></canvas>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon">
                        <i class="bi bi-bar-chart"></i> Monthly Payments
                    </h5>
                    <a href="payments.php" class="text-gold small text-decoration-none">View details →</a>
                </div>
                <canvas id="paymentChart" height="180"></canvas>
            </div>
        </div>
    </div>

    <!-- ======= Announcements Preview ======= -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon">
                        <i class="bi bi-megaphone"></i> Latest Announcements
                    </h5>
                    <a href="announcements.php" class="text-gold small text-decoration-none">View all →</a>
                </div>
                <ul class="list-group">
                    <?php if (mysqli_num_rows($announcements) == 0): ?>
                        <li class="list-group-item text-muted">No announcements available.</li>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($announcements)): ?>
                            <li class="list-group-item">📢 <?php echo $row['title']; ?></li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editManagerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dorm Manager Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editManagerForm">
                <div class="modal-body">
                    <input type="text" name="dorm_manager" class="form-control" value="<?= htmlspecialchars($manager) ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editManagerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('update_manager.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('managerName').innerText = formData.get('dorm_manager');
                    bootstrap.Modal.getInstance(document.getElementById('editManagerModal')).hide();
                    alert('Dorm Manager updated!');
                } else {
                    alert('Error: ' + data.message);
                }
            });
    });
</script>

<!-- ======= CHART.JS ======= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Room Occupancy Chart
    new Chart(document.getElementById('roomChart'), {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available'],
            datasets: [{
                data: [
                    <?php echo $total_occupied; ?>,
                    <?php echo $available_slots; ?>
                ],
                backgroundColor: ['#800000', '#FFD700'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Payments Chart
    new Chart(document.getElementById('paymentChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthlyLabels); ?>,
            datasets: [{
                label: 'Total Payments (₱)',
                data: <?php echo json_encode($monthlyData); ?>,
                backgroundColor: '#800000',
                borderRadius: 6
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>