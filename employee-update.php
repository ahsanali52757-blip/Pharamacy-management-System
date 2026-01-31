<?php
include "config.php";

// Enable error reporting (optional, for dev)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $qry1 = "SELECT user_id, first_name, last_name, birth_date, age, gender, role, join_date, salary, phone, email, address 
             FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($qry1);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: employee-view.php?error=notfound");
        exit();
    }
} else {
    header("Location: employee-view.php");
    exit();
}

if (isset($_POST['update'])) {
    $id = intval($_POST['eid']);
    $fname = $_POST['efname'];
    $lname = $_POST['elname'];
    $bdate = $_POST['ebdate'];
    $age = intval($_POST['eage']);
    $sex = $_POST['esex'];
    $role = $_POST['etype'];
    $jdate = $_POST['ejdate'];
    $sal = floatval($_POST['esal']);
    $phno = $_POST['ephno'];  // Phone treated as string
    $mail = $_POST['e_mail'];
    $add = $_POST['eadd'];

    $sql = "UPDATE users SET first_name=?, last_name=?, birth_date=?, age=?, gender=?, role=?, join_date=?, salary=?, phone=?, email=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssdsssi", $fname, $lname, $bdate, $age, $sex, $role, $jdate, $sal, $phno, $mail, $add, $id);

    if ($stmt->execute()) {
        header("Location: employee-view.php?updated=1");
        exit();
    } else {
        echo "<p style='color:red;'>Error! Unable to update.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form4.css">
<title>Update Employee</title>
</head>
<body>

<div class="sidenav">
    <h2 style="font-family:Arial; color:white; text-align:center;"> Zam Zam Pharmacy </h2>
    <a href="adminmainpage.php">Dashboard</a>
    <button class="dropdown-btn">Inventory<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="inventory-add.php">Add New Medicine</a>
        <a href="inventory-view.php">Manage Inventory</a>
    </div>
    <button class="dropdown-btn">Suppliers<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="supplier-add.php">Add New Supplier</a>
        <a href="supplier-view.php">Manage Suppliers</a>
    </div>
    <button class="dropdown-btn">Stock Purchase<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="purchase-add.php">Add New Purchase</a>
        <a href="purchase-view.php">Manage Purchases</a>
    </div>
    <button class="dropdown-btn">Employees<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="employee-add.php">Add New Employee</a>
        <a href="employee-view.php">Manage Employees</a>
    </div>
    <button class="dropdown-btn">Customers<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="customer-add.php">Add New Customer</a>
        <a href="customer-view.php">Manage Customers</a>
    </div>
    <a href="sales-view.php">View Sales Invoice Details</a>
    <a href="salesitems-view.php">View Sold Products Details</a>
    <a href="pos1.php">Add New Sale</a>
    <button class="dropdown-btn">Reports<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="stockreport.php">Medicines - Low Stock</a>
        <a href="expiryreport.php">Medicines - Soon to Expire</a>
        <a href="salesreport.php">Transactions Reports</a>
    </div>
</div>

<div class="topnav">
    <a href="logout.php">Logout</a>
</div>

<center>
    <div class="head">
        <h2>UPDATE EMPLOYEE DETAILS</h2>
    </div>
</center>

<div class="one">
    <div class="row">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . urlencode($row['user_id']); ?>" method="post">
            <div class="column">
                <p><label for="eid">Employee ID:</label><br>
                <input type="number" name="eid" value="<?php echo htmlspecialchars($row['user_id']); ?>" readonly></p>

                <p><label for="efname">First Name:</label><br>
                <input type="text" name="efname" value="<?php echo htmlspecialchars($row['first_name']); ?>" required></p>

                <p><label for="elname">Last Name:</label><br>
                <input type="text" name="elname" value="<?php echo htmlspecialchars($row['last_name']); ?>" required></p>

                <p><label for="ebdate">Date of Birth:</label><br>
                <input type="date" name="ebdate" value="<?php echo htmlspecialchars($row['birth_date']); ?>" required></p>

                <p><label for="eage">Age:</label><br>
                <input type="number" name="eage" value="<?php echo htmlspecialchars($row['age']); ?>" required></p>

                <p><label for="esex">Sex:</label><br>
                <select name="esex" required>
                    <option value="Male" <?php if($row['gender']=='Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($row['gender']=='Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if($row['gender']=='Other') echo 'selected'; ?>>Other</option>
                </select></p>
            </div>

            <div class="column">
                <p><label for="etype">Employee Type:</label><br>
                <select name="etype" required>
                    <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                    <option value="pharmacist" <?php if($row['role']=='pharmacist') echo 'selected'; ?>>Pharmacist</option>
                </select></p>

                <p><label for="ejdate">Date of Joining:</label><br>
                <input type="date" name="ejdate" value="<?php echo htmlspecialchars($row['join_date']); ?>" required></p>

                <p><label for="esal">Salary:</label><br>
                <input type="number" step="0.01" name="esal" value="<?php echo htmlspecialchars($row['salary']); ?>" required></p>

                <p><label for="ephno">Phone Number:</label><br>
                <input type="tel" name="ephno" value="<?php echo htmlspecialchars($row['phone']); ?>" required></p>

                <p><label for="e_mail">Email ID:</label><br>
                <input type="email" name="e_mail" value="<?php echo htmlspecialchars($row['email']); ?>" required></p>

                <p><label for="eadd">Address:</label><br>
                <input type="text" name="eadd" value="<?php echo htmlspecialchars($row['address']); ?>" required></p>
            </div>

            <input type="submit" name="update" value="Update">
        </form>
    </div>
</div>

</body>

<script>
// Dropdown functionality
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        content.style.display = (content.style.display === "block") ? "none" : "block";
    });
}
</script>
</html>
