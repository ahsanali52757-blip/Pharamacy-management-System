<?php
include "config.php";

if (isset($_GET['slid']) && isset($_GET['mid'])) {
    $sale_id = $_GET['slid'];
    $med_id = $_GET['mid'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM sales_items WHERE sale_id = ? AND med_id = ?");
    $stmt->bind_param("is", $sale_id, $med_id);


    if ($stmt->execute()) {
        // Redirect with the sale ID so the invoice can reload properly
        header("Location: pos2.php?sid=$sale_id");
        exit();
    } else {
        echo "Error while deleting item: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
