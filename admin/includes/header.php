<?php
session_start();
include '../config/conn.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== '00000') {
    header("Location: ../login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<style>
    footer {
        background-color: #7a1e1e;
        color: white;
        text-align: center;
        padding: 10px 0;
    }

    /* ===== Dashboard Styling (Clean White Theme) ===== */
    body {
        background-color: #fff;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDORM | <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/circle-logo.png">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-maroon sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../assets/img/logo.png" alt="Logo" height="50" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <!-- Dropdown for Manage Pages -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                            <li><a class="dropdown-item" href="contracts.php">Contracts</a></li>
                            <li><a class="dropdown-item" href="applications.php">Applications</a></li>
                            <li><a class="dropdown-item" href="rooms.php">Rooms</a></li>
                            <li><a class="dropdown-item" href="payments.php">Payments</a></li>
                            <li><a class="dropdown-item" href="requests.php">Requests</a></li>
                            <li><a class="dropdown-item" href="announcements.php">Announcements</a></li>
                            <li><a class="dropdown-item" href="inventory.php">Inventory</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link text-danger btn btn-gold ms-3" href="../includes/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>