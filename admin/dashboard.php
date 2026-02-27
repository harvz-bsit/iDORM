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
?>

<div class="container py-5 min-vh-100">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Welcome back, <span class="text-gold">Admin!</span> 👋</h1>
        <p class="lead">Here’s a quick overview of the Dormitory</p>
        <div class="divider"></div>
    </div>

    <!-- ======= Dashboard Cards ======= -->
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