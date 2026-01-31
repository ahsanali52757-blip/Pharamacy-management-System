<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include "config.php";

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_link'])) {
    $username = mysqli_real_escape_string($conn, $_POST['uname']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Verify user exists
    $query = "SELECT * FROM users WHERE username='$username' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $email = $user['email'];

        // Generate token and timestamps
        $token = bin2hex(random_bytes(16));
        $created_at = date('Y-m-d H:i:s');
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in password_resets table including expires_at
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, created_at, expires_at) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error); // Debugging info if table/column is wrong
        }

        $stmt->bind_param("ssss", $email, $token, $created_at, $expires_at);
        $stmt->execute();

        // Send email with reset link
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Pharmacyzamzam52@gmail.com'; // Your email
            $mail->Password = 'xtmy kofp ikgj evhv'; // App password (NOT your actual Gmail password)
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('Pharmacyzamzam52@gmail.com', 'Zam Zam Pharmacy');
            $mail->addAddress($email);

            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/PHARMACY/reset_password.php?token=$token";

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click <a href='$resetLink'>here</a> to reset your password.";

            $mail->send();
            $feedback = "<p style='color:green;'>Reset link sent to $email</p>";
        } catch (Exception $e) {
            $feedback = "<p style='color:red;'>Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        $feedback = "<p style='color:red;'>Invalid username or role.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login1.css">
</head>
<body>
    <br><br><br><br><br><br><br><br><br><br>
<div class="container">
    <form method="post" action="">
        <div id="div_login">
            <h1>Reset Password</h1>
            <center>
                <div><input type="text" name="uname" placeholder="Username" required /></div>
                <div>
                    <select name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="pharmacist">Pharmacist</option>
                    </select>
                </div>
                <div><input type="submit" name="send_link" value="Send Reset Link" /></div>
                <div><a href="mainpage.php">Back to Login</a></div>
                <div><?= $feedback ?></div>
            </center>
        </div>
    </form>
</div>
</body>
</html>
