<?php
	include "config.php";
	session_start();

	// Fetch pharmacist's first name
	$sql = "SELECT first_name FROM users WHERE user_id = '$_SESSION[user]'";
	$result = $conn->query($sql);
	$row = $result->fetch_row();
	$ename = $row[0];

	// Search functionality
	if(isset($_POST['search'])) {
		$search = $_POST['valuetosearch'];
		$query = "SELECT c_id, c_fname, c_lname, c_phno FROM customer 
				  WHERE c_id LIKE '%".$search."%' OR c_fname LIKE '%".$search."%' OR c_lname LIKE '%".$search."%' OR c_phno LIKE '%".$search."%'";
		$search_result = filtertable($query, $conn);
	} else {
		$query = "SELECT c_id, c_fname, c_lname, c_phno FROM customer";
		$search_result = filtertable($query, $conn);
	}

	// Function to fetch table data
	function filtertable($query, $conn) {
		$filter_result = mysqli_query($conn, $query);
		return $filter_result;
	}
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="nav2.css">
	<link rel="stylesheet" type="text/css" href="table1.css">
	<link rel="stylesheet" type="text/css" href="form2.css">
	<title>Customers</title>
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
		<a href="logout1.php">Logout </a>
	</div>
	
	<center>
		<div class="head">
			<h2> CUSTOMER LIST </h2>
		</div>
		
		<form method="post">
			<input type="text" name="valuetosearch" placeholder="Enter any value to Search" style="width:400px; margin-left:250px;">&nbsp;&nbsp;&nbsp;
			<input type="submit" name="search" value="Search">
			<br><br>
		</form>
	</center>

	<table align="right" id="table1" style="margin-right:100px;">
		<tr>
			<th>Customer ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Phone Number</th>
		</tr>
		
	<?php
		if ($search_result->num_rows > 0) {
			while($row = $search_result->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . $row["c_id"] . "</td>";
				echo "<td>" . $row["c_fname"] . "</td>";
				echo "<td>" . $row["c_lname"] . "</td>";
				echo "<td>" . $row["c_phno"] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<tr><td colspan='4'>No customers found</td></tr>";
		}
		
		$conn->close();
	?>
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
