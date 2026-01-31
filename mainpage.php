<!DOCTYPE html>
<html>

<head>
    <title>Zam Zam Pharmacy - Login</title>
    <link rel="stylesheet" type="text/css" href="login1.css">
    <style>
        select {
            padding: 10px;
            width: 92%;
            margin-bottom: 10px;
        }

        a.forgot-link {
            text-decoration: none;
            color: blue;
            font-size: 14px;
        }

        a.forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Zam Zam Pharmacy</h1>
        <p style="margin-top:-20px;line-height:1;font-size:30px;">Pharmacy Management System</p>
    </div>

    <br><br><br><br>

    <div class="container">
        <form method="post" action="">
            <div id="div_login">
                <h1>Login</h1>
                <center>
                    <div>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="pharmacist">Pharmacist</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" class="textbox" id="uname" name="uname" placeholder="Username" required />
                    </div>
                    <div>
                        <input type="password" class="textbox" id="pwd" name="pwd" placeholder="Password" required />
                    </div>
                    <br>
                    <br>
                    <div>
                        <input type="submit" value="Login" name="submit" id="submit" />
                    </div>
                    <p style="margin-top:10px;">
                        <a class="forgot-link" href="forgot_password.php">Forgot Password?</a>
                    </p>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config.php";

if (isset($_POST['submit'])) {
    $role = $_POST['role'];
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $enteredPassword = $_POST['pwd'];

    if ($uname != "" && $enteredPassword != "" && $role != "") {
        session_start();

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $uname, $role);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($enteredPassword, $user['password'])) {
                    $_SESSION['user'] = $uname;

                    if ($role === 'admin') {
                        header('Location: adminmainpage.php');
                    } else {
                        header('Location: pharmmainpage.php');
                    }
                    exit();
                } else {
                    echo "<p style='color:red;'>Incorrect password.</p>";
                }
            } else {
                echo "<p style='color:red;'>User not found or role mismatch.</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color:red;'>SQL Prepare failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }

    $conn->close();
}
?>


                </center>
            </div>
        </form>
    </div>

    <div class="footer">
        <br>
        Database Systems Project.
        <br><br>
    </div>
</body>

</html>
