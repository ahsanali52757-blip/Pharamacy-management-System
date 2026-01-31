<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form4.css">
<title>
Purchases
</title>
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
<h2> ADD PURCHASE DETAILS</h2>
</div>
</center>

<br><br><br><br><br><br><br><br>

<div class="one row">
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

<?php
include "config.php";

if (isset($_POST['add'])) {
	$sid = mysqli_real_escape_string($conn, $_POST['sid']);
	$mid = mysqli_real_escape_string($conn, $_POST['mid']);
	$qty = mysqli_real_escape_string($conn, $_POST['pqty']);
	$cost = mysqli_real_escape_string($conn, $_POST['pcost']);
	$pdate = mysqli_real_escape_string($conn, $_POST['pdate']);
	$mdate = mysqli_real_escape_string($conn, $_POST['mdate']);
	$edate = mysqli_real_escape_string($conn, $_POST['edate']);

	$sql = "INSERT INTO purchase (sup_id, med_id, p_qty, p_cost, pur_date, mfg_date, exp_date) 
			VALUES ('$sid', '$mid', '$qty', '$cost', '$pdate', '$mdate', '$edate')";
	if (mysqli_query($conn, $sql)) {
		echo "<p style='font-size:8;'>Purchase details successfully added!</p>";
	} else {
		echo "<p style='font-size:8;color:red;'>Error! Check details.</p>";
	}
}

$conn->close();
?>

		<div class="column">
			<p>
				<label for="sid">Supplier ID:</label><br>
				<input type="number" name="sid" required>
			</p>
			<p>
				<label for="mid">Medicine ID:</label><br>
				<input type="number" name="mid" required>
			</p>
			<p>
				<label for="pqty">Purchase Quantity:</label><br>
				<input type="number" name="pqty" required>
			</p>
		</div>
		<div class="column">
			<p>
				<label for="pcost">Purchase Cost:</label><br>
				<input type="number" step="0.01" name="pcost" required>
			</p>
			<p>
				<label for="pdate">Date of Purchase:</label><br>
				<input type="date" name="pdate" required>
			</p>
			<p>
				<label for="mdate">Manufacturing Date:</label><br>
				<input type="date" name="mdate" required>
			</p>
			<p>
				<label for="edate">Expiry Date:</label><br>
				<input type="date" name="edate" required>
			</p>
		</div>
		<input type="submit" name="add" value="Add Purchase">
	</form>
</div>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
	dropdown[i].addEventListener("click", function () {
		this.classList.toggle("active");
		var dropdownContent = this.nextElementSibling;
		dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
	});
}
</script>

</body>
</html>
