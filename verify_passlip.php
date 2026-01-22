<?php
include 'config/conn.php';

$verificationMessage = '';
$showForm = false;

if (!isset($_GET['code'])) {
    $verificationMessage = "<h2 class='text-danger'>Invalid Code</h2>";
} else {
    $code = $_GET['code'];

    $stmt = $conn->prepare("
        SELECT * FROM passlips
        WHERE verification_code=? 
          AND status='Approved'
          AND used=0
          AND expires_at > NOW()");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $p = $res->fetch_assoc();
        $verificationMessage = "
            <h2 class='text-success text-center'>VALID PASS SLIP</h2>
            <p><strong>Destination:</strong> {$p['destination']}</p>
            <p><strong>Return:</strong> {$p['return_date']} {$p['return_time']}</p>";
        $showForm = true;
    } else {
        $verificationMessage = "<h2 class='text-danger text-center'>INVALID OR EXPIRED</h2>";
    }
}

// Handle mark as used
if (isset($_POST['mark'])) {
    $c = $_POST['code'];
    $u = $conn->prepare("UPDATE passlips SET used=1 WHERE verification_code=?");
    $u->bind_param("s", $c);
    $u->execute();
    $verificationMessage = "<h2 class='text-primary text-center'>Pass slip marked as used.</h2>";
    $showForm = false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IDORM | ISPSC Dormitory</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/circle-logo.png">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-maroon sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/img/logo.png" alt="Logo" height="50" class="me-2">
            </a>
        </div>
    </nav>
    <div class="container my-5 min-vh-100">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3>IDORM System - Pass Slip Verification</h3>
                <p class="mb-0" style="font-size:13px;">Ilocos Sur Polytechnic State College</p>
            </div>
            <div class="card-body ">
                <?= $verificationMessage ?>

                <?php if ($showForm): ?>
                    <form method="post" class="mt-3 text-center">
                        <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
                        <button type="submit" name="mark" class="btn btn-success">Mark as Used</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-center text-muted" style="font-size:12px;">
                © <?= date('Y') ?> IDORM System | Ilocos Sur Polytechnic State College
            </div>
        </div>
    </div>

    <?php include 'includes/landing/footer.php'; ?>