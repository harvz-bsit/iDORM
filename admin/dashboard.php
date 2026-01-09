<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<div class="container py-5">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Welcome back, <span class="text-gold">Admin!</span> 👋</h1>
        <p class="lead">Here’s a quick overview of the Dormitory</p>
        <div class="divider"></div>
    </div>

    <!-- ======= Dashboard Cards ======= -->
    <div class="row g-4">
        <!-- Available Slots -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                <i class="bi bi-door-open display-5 text-success mb-2"></i>
                <h5 class="fw-semibold text-maroon">Available Slots</h5>
                <h2 class="fw-bold text-dark">12</h2>
                <p class="text-muted mb-0">Rooms ready for occupancy</p>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                <i class="bi bi-house-door display-5 text-primary mb-2"></i>
                <h5 class="fw-semibold text-maroon">Occupied Rooms</h5>
                <h2 class="fw-bold text-dark">48</h2>
                <p class="text-muted mb-0">Currently assigned</p>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                <i class="bi bi-person-check display-5 text-warning mb-2"></i>
                <h5 class="fw-semibold text-maroon">Pending Approvals</h5>
                <h2 class="fw-bold text-dark">5</h2>
                <p class="text-muted mb-0">Applicants waiting review</p>
            </div>
        </div>

        <!-- Outstanding Payments -->
        <div class="col-md-3">
            <div class="dashboard-card h-100 p-4 text-center shadow-sm">
                <i class="bi bi-cash-stack display-5 text-danger mb-2"></i>
                <h5 class="fw-semibold text-maroon">Outstanding Payments</h5>
                <h2 class="fw-bold text-dark">₱12,500</h2>
                <p class="text-muted mb-0">Pending this month</p>
            </div>
        </div>
    </div>

    <!-- ======= Charts Section ======= -->
    <div class="row g-4 mt-4">
        <!-- Room Occupancy Overview -->
        <div class="col-lg-6">
            <div class="dashboard-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon"><i class="bi bi-building"></i> Room Occupancy Overview</h5>
                    <a href="rooms.php" class="text-gold small text-decoration-none">View details →</a>
                </div>
                <canvas id="roomChart" height="180"></canvas>
            </div>
        </div>

        <!-- Monthly Payment Summary -->
        <div class="col-lg-6">
            <div class="dashboard-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-maroon"><i class="bi bi-bar-chart"></i> Monthly Payments</h5>
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
                    <h5 class="fw-semibold text-maroon"><i class="bi bi-megaphone"></i> Latest Announcements</h5>
                    <a href="announcements.php" class="text-gold small text-decoration-none">View all →</a>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">🕓 Power maintenance – October 30 (8AM–2PM)</li>
                    <li class="list-group-item">💧 Water tank cleaning – November 1</li>
                    <li class="list-group-item">📄 Midterm payment deadline – November 5</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ======= CHART.JS ======= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Room Occupancy Overview
    const roomCtx = document.getElementById('roomChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available', 'Under Maintenance'],
            datasets: [{
                data: [48, 12, 3],
                backgroundColor: ['#800000', '#FFD700', '#006400'],
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

    // Monthly Payment Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: ['June', 'July', 'August', 'September', 'October'],
            datasets: [{
                label: 'Total Payments (₱)',
                data: [15000, 18000, 17500, 20000, 22000],
                backgroundColor: '#800000',
                borderRadius: 6,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>