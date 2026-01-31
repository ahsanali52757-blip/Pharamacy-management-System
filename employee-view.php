<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="table1.css">
<title>Employees</title>
</head>
<body>

		<div class="sidenav">
			<h2 style="font-family:Arial; color:white; text-align:center;"> Zam Zam Pharmacy </h2>
			<a href="adminmainpage.php">Dashboard</a>
			<button class="dropdown-btn">Inventory
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="inventory-add.php">Add New Medicine</a>
				<a href="inventory-view.php">Manage Inventory</a>
			</div>
			<button class="dropdown-btn">Suppliers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="supplier-add.php">Add New Supplier</a>
				<a href="supplier-view.php">Manage Suppliers</a>
			</div>
			<button class="dropdown-btn">Stock Purchase
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="purchase-add.php">Add New Purchase</a>
				<a href="purchase-view.php">Manage Purchases</a>
			</div>			
			<button class="dropdown-btn">Employees
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="employee-add.php">Add New Employee</a>
				<a href="employee-view.php">Manage Employees</a>
			</div>			
			<button class="dropdown-btn">Customers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="customer-add.php">Add New Customer</a>
				<a href="customer-view.php">Manage Customers</a>
			</div>
			<a href="sales-view.php">View Sales Invoice Details</a>
			<a href="salesitems-view.php">View Sold Products Details</a>
			<a href="pos1.php">Add New Sale</a>			
			<button class="dropdown-btn">Reports
			<i class="down"></i>
			</button>
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
        <h2> EMPLOYEE LIST</h2>
    </div>
</center>

<table align="right" id="table1" style="margin-right:20px;">
    <tr>
        <th>Employee ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Employee Type</th>
        <th>Date of Joining</th>
        <th>Salary</th>
        <th>Phone Number</th>
        <th>Email Address</th>
        <th>Home Address</th>
        <th>Action</th>
    </tr>

<?php
include "config.php";

// Adjust this WHERE clause as needed (e.g., exclude admin, or show only pharmacists)
$sql = "SELECT user_id, first_name, last_name, birth_date, age, gender, role, join_date, salary, phone, email, address 
        FROM users 
        WHERE role <> 'admin'"; // Exclude admin users if needed

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["birth_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["join_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["salary"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
        echo "<td align='center'>";
        echo "<a class='button1 edit-btn' href='employee-update.php?id=" . urlencode($row['user_id']) . "'>Edit</a> ";
        echo "<a onclick='return confirm(\"Are you sure to delete?\");' class='button1 del-btn' href='employee-delete.php?id=" . urlencode($row['user_id']) . "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='13'>No employees found.</td></tr>";
}

$conn->close();
?>

</table>

</body>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;
for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>
</html>
