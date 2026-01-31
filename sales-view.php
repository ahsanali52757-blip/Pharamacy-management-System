<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="table1.css">
<title>Sales Invoice</title>
</head>

<body>

	<div class="sidenav">
		<h2 style="font-family:Arial; color:white; text-align:center;"> Zam Zam Pharmacy </h2>
		<a href="adminmainpage.php">Dashboard</a>
		<button class="dropdown-btn">Inventory <i class="down"></i></button>
		<div class="dropdown-container">
			<a href="inventory-add.php">Add New Medicine</a>
			<a href="inventory-view.php">Manage Inventory</a>
		</div>
		<button class="dropdown-btn">Suppliers <i class="down"></i></button>
		<div class="dropdown-container">
			<a href="supplier-add.php">Add New Supplier</a>
			<a href="supplier-view.php">Manage Suppliers</a>
		</div>
		<button class="dropdown-btn">Stock Purchase <i class="down"></i></button>
		<div class="dropdown-container">
			<a href="purchase-add.php">Add New Purchase</a>
			<a href="purchase-view.php">Manage Purchases</a>
		</div>
		<button class="dropdown-btn">Employees <i class="down"></i></button>
		<div class="dropdown-container">
			<a href="employee-add.php">Add New Employee</a>
			<a href="employee-view.php">Manage Employees</a>
		</div>
		<button class="dropdown-btn">Customers <i class="down"></i></button>
		<div class="dropdown-container">
			<a href="customer-add.php">Add New Customer</a>
			<a href="customer-view.php">Manage Customers</a>
		</div>
		<a href="sales-view.php">View Sales Invoice Details</a>
		<a href="salesitems-view.php">View Sold Products Details</a>
		<a href="pos1.php">Add New Sale</a>
		<button class="dropdown-btn">Reports <i class="down"></i></button>
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
		<h2>SALES INVOICE DETAILS</h2>
	</div>
	</center>

	<table align="right" id="table1" style="margin-right:100px;">
		<tr>
			<th>Sale ID</th>
			<th>Customer ID</th>
			<th>Date and Time</th>
			<th>Sale Amount</th>
			<th>Employee Name</th>
		</tr>

<?php
include "config.php";

$sql = "SELECT s.sale_id, s.c_id, s.s_date, s.s_time, s.total_amt, u.first_name, u.last_name
        FROM sales s
        JOIN users u ON s.user_id = u.user_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		echo "<tr>";
			echo "<td>" . $row["sale_id"] . "</td>";
			echo "<td>" . $row["c_id"] . "</td>";
			echo "<td>" . $row["s_date"] . "&nbsp;&nbsp;" . $row["s_time"] . "</td>";
			echo "<td>" . $row["total_amt"] . "</td>";
			echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>"; // full name
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "<tr><td colspan='5'>No sales records found.</td></tr></table>";
}

$conn->close();
?>


	</table>

</body>

<script>
	var dropdown = document.getElementsByClassName("dropdown-btn");
	for (var i = 0; i < dropdown.length; i++) {
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
