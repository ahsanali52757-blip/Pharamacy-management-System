<?php
include "config.php";
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch pharmacist's first name using username from session
$username = $_SESSION['user'];
$sql1 = "SELECT first_name FROM users WHERE username = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $username);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();
$ename = $row1['first_name'] ?? 'Pharmacist';

// Search handling
if (isset($_POST['search'])) {
    $search = $_POST['valuetosearch'];

    // Call stored procedure with input parameter
    $conn->query("SET @p0 = '$search'");
    $search_result = $conn->query("CALL SEARCH_INVENTORY(@p0)");
} else {
    // Default: select all inventory
    $search_result = $conn->query("
        SELECT 
            med_id AS medid,
            med_name AS medname,
            med_qty AS medqty,
            category AS medcategory,
            med_price AS medprice,
            location_rack AS medlocation 
        FROM meds
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="table1.css">
    <link rel="stylesheet" type="text/css" href="nav2.css">
    <link rel="stylesheet" type="text/css" href="form2.css">
    <title>Inventory</title>
</head>
<body>

<div class="sidenav">
    <h2 style="font-family:Arial; color:white; text-align:center;"> Zam Zam Pharmacy </h2>
    <a href="pharmmainpage.php">Dashboard</a>
    <a href="pharm-inventory.php">View Inventory</a>
    <a href="pharm-pos1.php">Add New Sale</a>
    <button class="dropdown-btn">Customers
        <i class="down"></i>
    </button>
    <div class="dropdown-container">
        <a href="pharm-customer.php">Add New Customer</a>
        <a href="pharm-customer-view.php">View Customers</a>
    </div>
</div>

<div class="topnav">
    <a href="logout1.php">Logout</a>
</div>

<center>
    <div class="head">
        <h2> MEDICINE INVENTORY </h2>
        <p>Welcome, <strong><?php echo htmlspecialchars($ename); ?></strong></p>
    </div>

    <form method="post">
        <input type="text" name="valuetosearch" placeholder="Enter any value to Search" style="width:400px; margin-left:250px;">
        <input type="submit" name="search" value="Search">
        <br><br>
    </form>
</center>

<table align="right" id="table1" style="margin-top:20px; margin-right:100px;">
    <tr>
        <th>Medicine ID</th>
        <th>Medicine Name</th>
        <th>Quantity Available</th>
        <th>Category</th>
        <th>Price</th>
        <th>Location in Store</th>
    </tr>

    <?php
    if ($search_result && $search_result->num_rows > 0) {
        while ($row = $search_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["medid"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["medname"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["medqty"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["medcategory"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["medprice"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["medlocation"]) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No medicines found.</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        const dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
}
</script>

</html>
