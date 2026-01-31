<?php
include 'config.php';

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT email, created_at, expires_at, used FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " - SQL: " . $sql);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $resetData = $result->fetch_assoc();

        // Convert expires_at to timestamp
        $expiresAt = strtotime($resetData['expires_at']);
        $used = $resetData['used'];

        if ($used == 1) {
            $error = 'This password reset link has already been used.';
        } elseif (time() > $expiresAt) {
            $error = 'This password reset link has expired.';
        } else {
            // Valid token; handle new password submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $email = $resetData['email'];

                $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                if (!$updateStmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $updateStmt->bind_param("ss", $newPassword, $email);
                if ($updateStmt->execute()) {
                    $markUsedStmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
                    if (!$markUsedStmt) {
                        die("Prepare failed: " . $conn->error);
                    }

                    $markUsedStmt->bind_param("s", $token);
                    $markUsedStmt->execute();
                    $markUsedStmt->close();

                    $success = 'Your password has been successfully reset. You can now <a href="mainpage.php">login</a>.';
                } else {
                    $error = 'Failed to update the password. Please try again later.';
                }

                $updateStmt->close();
            }
        }
    } else {
        $error = 'Invalid password reset token.';
    }

    $stmt->close();
} else {
    $error = 'No password reset token provided.';
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="login1.css">
</head>
<body>
<div class="container">
    <form method="post" action="">
        <div id="div_login">
            <h1>Reset Password</h1>
            <?php if ($error): ?>
                <div style="color: red; margin-bottom: 15px;"> <?= $error ?> </div>
            <?php elseif ($success): ?>
                <div style="color: green; margin-bottom: 15px;"> <?= $success ?> </div>
            <?php else: ?>
                <div>
                    <input type="password" name="new_password" placeholder="Enter New Password" required />
                </div>
                <div>
                    <input type="submit" value="Reset Password" />
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
