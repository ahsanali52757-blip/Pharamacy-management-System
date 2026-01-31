<?php
include "config.php";

$result = mysqli_query($conn, "SELECT user_id, password FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['user_id'];
    $plainPassword = $row['password'];

    // Only hash if it's not already hashed (length < 60 is a basic check)
    if (strlen($plainPassword) < 60) {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password = '$hashedPassword' WHERE user_id = $id");

        if ($update) {
            echo "Password hashed for user_id: $id<br>";
        } else {
            echo "Failed to update password for user_id: $id<br>";
        }
    }
}
?>
