<?php
    include "config.php";

    // Using 'user_id' as the identifier for the user
    $sql = "DELETE FROM users WHERE user_id='$_GET[id]'";

    if ($conn->query($sql)) {
        header("Location: employee-view.php"); // Redirecting to user view page
    } else {
        echo "Error: " . $conn->error; // Displaying any potential error
    }
?>
