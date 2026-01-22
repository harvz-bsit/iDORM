<?php
require '../vendor/autoload.php';
include '../config/conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if (isset($_POST['action']) && isset($_POST['student_id'])) {

    $action = $_POST['action'];
    $id = intval($_POST['student_id']);

    if ($action === 'approve') {
        $status = 'Approved';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    // Update status
    $query = $conn->prepare("UPDATE application_approvals SET status = ? WHERE student_id = ?");
    $query->bind_param("si", $status, $id);

    if ($query->execute()) {

        // Detect environment
        $isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');

        $baseUrl = $isLocal
            ? "https://localhost/idorm"
            : "https://idorm.ispsc.edu.ph";

        $loginUrl = $baseUrl . "/login";

        // Get user email
        $emailQuery = $conn->prepare("
            SELECT u.email, upi.full_name
            FROM users u
            JOIN user_personal_information upi
              ON u.student_id = upi.student_id
            WHERE u.student_id = ?
        ");
        $emailQuery->bind_param("i", $id);
        $emailQuery->execute();
        $emailResult = $emailQuery->get_result();

        if ($emailResult->num_rows > 0) {
            $userData = $emailResult->fetch_assoc();

            // ================= EMAIL =================
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'idormsystem@gmail.com';
                $mail->Password = 'yyoyfrqcybomxhkj';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('idormsystem@gmail.com', 'IDORM System');
                $mail->addAddress($userData['email']);

                // EMBED LOCAL LOGO
                $mail->addEmbeddedImage('../assets/img/circle-logo.png', 'idormlogo');

                $mail->isHTML(true);
                $mail->Subject = 'Dormitory Application Status';

                $mail->Body = "
<!DOCTYPE html>
<html>
<body style='margin:0; padding:0; background:#f4f6f9; font-family:Arial, sans-serif;'>
<table width='100%' style='background:#f4f6f9; padding:20px;'>
<tr><td align='center'>
<table width='600' style='background:#ffffff; border-radius:8px; overflow:hidden;'>

<tr>
<td style='padding:20px; border-bottom:1px solid #eee;'>
<table width='100%'><tr>
<td width='60'>
<img src='cid:idormlogo' width='50' alt='IDORM Logo'>
</td>
<td>
<h2 style='margin:0;'>IDORM System</h2>
<p style='margin:0; font-size:12px; color:#888;'>Ilocos Sur Polytechnic State College</p>
</td>
</tr></table>
</td>
</tr>

<tr>
<td style='padding:30px;'>
<h3>Hello {$userData['full_name']},</h3>
<p>Your dormitory application has been <b>{$status}</b>.</p>
<p>Please log in to your account to sign your dormitory contract.</p>

<p style='text-align:center; margin:30px 0;'>
<a href='{$loginUrl}' style='background:#2563eb;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:bold;'>
Login to IDORM
</a>
</p>

<p style='font-size:12px;color:#777;'>If you did not apply, please ignore this email.</p>
</td>
</tr>

<tr>
<td style='background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#777;'>
<p>© " . date('Y') . " IDORM System</p>
<p>Ilocos Sur Polytechnic State College - Main Campus</p>
<p>San Nicolas, Candon City, Ilocos Sur</p>
<p style='font-size:11px;color:#aaa;'>This is an automated email. Please do not reply.</p>
</td>
</tr>

</table>
</td></tr>
</table>
</body>
</html>";

                if ($mail->send()) {
                    echo json_encode(['status' => 'success', 'message' => "Applicant has been {$status}."]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Email could not be sent.']);
                }
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                echo json_encode(['status' => 'error', 'message' => 'Email could not be sent.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User email not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }
}
