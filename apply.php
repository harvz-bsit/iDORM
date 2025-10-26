<?php
session_start();
if (isset($_SESSION['boarder_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Room | IDORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

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
            padding: 40px;
        }

        .apply-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 700px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
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

        .form-control {
            background: rgba(255, 255, 255, 0.12);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px var(--gold);
            background: rgba(255, 255, 255, 0.18);
        }

        .btn-apply {
            background: linear-gradient(135deg, var(--gold), var(--green));
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-apply:hover {
            background: linear-gradient(135deg, var(--green), var(--gold));
            transform: translateY(-3px);
        }

        h3 {
            color: var(--gold);
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: 500;
            color: var(--gold);
            margin-bottom: 6px;
        }

        .apply-card img {
            height: 80px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="apply-card">
        <div class="text-center">
            <img src="assets/img/logo.png" alt="IDORM Logo">
        </div>
        <h3>Dormitory Application Form</h3>
        <p class="text-center text-light mb-4">Please fill out the information below to apply for dorm accommodation.</p>

        <form action="submit_application.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" placeholder="e.g. Maria Dela Cruz" required>
                </div>
                <div class="col-md-6">
                    <label>Student ID</label>
                    <input type="text" name="student_id" class="form-control" placeholder="e.g. 2025-12345" required>
                </div>

                <div class="col-md-6">
                    <label>Course / Program</label>
                    <input type="text" name="course" class="form-control" placeholder="e.g. BS Information Technology" required>
                </div>
                <div class="col-md-6">
                    <label>Year Level</label>
                    <select name="year_level" class="form-control" required>
                        <option value="">Select</option>
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Contact Number</label>
                    <input type="text" name="contact" class="form-control" placeholder="09XXXXXXXXX" required>
                </div>
                <div class="col-md-6">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="e.g. maria@gmail.com" required>
                </div>

                <div class="col-md-12">
                    <label>Home Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Complete Address" required>
                </div>

                <div class="col-md-6">
                    <label>Guardian Name</label>
                    <input type="text" name="guardian" class="form-control" placeholder="Parent or Guardian Name" required>
                </div>
                <div class="col-md-6">
                    <label>Guardian Contact</label>
                    <input type="text" name="guardian_contact" class="form-control" placeholder="09XXXXXXXXX" required>
                </div>

                <div class="col-md-12">
                    <label>Preferred Room Type</label>
                    <select name="room_type" class="form-control" required>
                        <option value="">Select Room</option>
                        <option>Single Room</option>
                        <option>Double Room</option>
                        <option>Quad Room</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-apply w-100 py-2">Submit Application</button>
                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none text-light">← Back to Home</a>
        </div>
    </div>

</body>

</html>