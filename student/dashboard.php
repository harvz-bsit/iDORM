<?php
$pageTitle = "Dashboard";
include 'includes/header.php';
?>

<style>
    /* ===== Dashboard Styling (Clean White Theme) ===== */
    body {
        background-color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-weight: 700;
        color: #7a1e1e;
        /* maroon */
    }

    .dashboard-header p {
        color: #555;
    }

    .divider {
        width: 70px;
        height: 4px;
        border-radius: 2px;
        background: linear-gradient(90deg, #800000, #FFD700, #006400);
        margin: 20px auto;
    }

    /* Cards */
    .dashboard-card {
        border: none;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .dashboard-card h5 {
        color: #800000;
        font-weight: 600;
    }

    .dashboard-card p {
        color: #333;
        font-size: 1rem;
    }

    /* Announcements */
    .announcements {
        background-color: #fafafa;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .announcements h4 {
        color: #006400;
        font-weight: 700;
    }

    .announcements-card {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .announcements-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
    }

    .list-group-item {
        transition: background-color 0.2s;
    }

    .list-group-item:hover {
        background-color: #fafafa;
    }

    .list-group-item {
        background-color: #fff;
        border: 1px solid #eee;
        color: #333;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f7f7f7;
    }

    /* Buttons */
    .btn-gold {
        background-color: #FFD700;
        color: #000;
        border: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-gold:hover {
        background-color: #e6c200;
    }

    .btn-outline-maroon {
        color: #800000;
        border: 1.5px solid #800000;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-outline-maroon:hover {
        background-color: #800000;
        color: #fff;
    }
</style>

<div class="container py-5">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Welcome back, <span class="text-gold"><?php echo $_SESSION['student_name'] ?? 'Student'; ?></span> 👋</h1>
        <p class="lead">Here’s your dormitory overview at ISPSC Main Campus.</p>
        <div class="divider"></div>
    </div>

    <!-- Cards -->
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
        <a href="requests.php" class="btn btn-outline-maroon px-4 py-2 rounded-pill mx-2 mb-2">Send Request</a>
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

</div>

<?php include 'includes/footer.php'; ?>