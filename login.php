<?php
session_start();
if (isset($_SESSION['student_id'])) {
    header("Location: student/dashboard.php");
    exit;
}

$error = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | IDORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/circle-logo.png">

    <style>
        :root {
            --maroon: #7a1e1e;
            --green: #44693D;
            --gold: #e3b23c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, rgba(86, 24, 24, 0.8), rgba(52, 65, 42, 0.8), rgba(112, 93, 49, 0.7)),
                url('assets/img/dorm-bg.jpg') center/cover no-repeat;
            backdrop-filter: blur(5px);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 420px;
            color: white;
            text-align: center;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            animation: fadeUp 1s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card img {
            height: 80px;
            margin-bottom: 15px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--gold), var(--green));
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--green), var(--gold));
            transform: translateY(-3px);
        }

        .forgot-link {
            color: var(--gold);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <!-- Back to Landing -->
        <div class="d-flex justify-content-start w-100">
            <a href="index.php" class="btn btn-sm btn-outline-light mb-3">←</a>
        </div>
        <img src="assets/img/logo.png" alt="IDORM Logo">
        <h3 class="fw-bold text-gold mb-3">Boarder Login</h3>
        <p class="text-light mb-4">Sign in with your Student ID and password</p>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="includes/processes.php" method="POST">
            <input type="text" name="student_id" class="form-control" placeholder="Student ID" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-login w-100 py-2 mt-2">Sign In</button>
        </form>

        <div class="mt-3">
            <a href="#" class="forgot-link">Forgot Password?</a>
        </div>

        <hr class="my-4 text-light">
        <p class="small mb-0">© <?php echo date('Y'); ?> IDORM | Ilocos Sur Polytechnic State College</p>
    </div>
</body>

</html>