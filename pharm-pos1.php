<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<link rel="stylesheet" type="text/css" href="table2.css">
<title>
New Sales
</title>
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

	<?php
		include "config.php";
		session_start();

		// Updated query to fetch pharmacist's first name from 'users' table
		$sql = "SELECT first_name FROM users WHERE user_id='$_SESSION[user]'";
		$result = $conn->query($sql);
		$row = $result->fetch_row();
		
		$ename = $row[0];
	?>

	<div class="topnav">
		<a href="logout1.php">Logout </a>
	</div>
	
	<center>
		<div class="head">
			<h2> POINT OF SALE </h2>
		</div>
	</center>

	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<center>
		
		<select id="cid" name="cid">
			<option value="0" selected="selected">*Select Customer ID (only once for a customer's sales)</option>
					
		<?php	
			
		include "config.php";
		$qry="SELECT c_id FROM customer";
		$result= $conn->query($qry);
		echo mysqli_error($conn);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<option>".$row["c_id"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="custadd" value="Start New Sale">
	</form>
	
		
	<?php
	
		
		
		// Fetch user_id from the 'users' table now
		$qry1 = "SELECT user_id from users WHERE username='$_SESSION[user]'";
		$result1 = $conn->query($qry1);
		$row1 = $result1->fetch_row();
		$eid = $row1[0]; // Now 'user_id' is stored as $eid
	
			if(isset($_GET['sid'])) 
			{
				$sid = $_GET['sid'];
			}
			
			if(isset($_POST['cid']))
				$cid = $_POST['cid'];
		

		
		if(isset($_POST['custadd'])) {
			
			// Add more debugging
			echo "<div style='background:lightblue; padding:10px; margin:10px;'>";
			echo "<strong>INSERTION DEBUG:</strong><br>";
			echo "Customer ID being inserted: $cid<br>";
			echo "Employee ID being inserted: $eid<br>";
			
			// Handle both registered customers and walk-ins
			if($cid == 0) {
				// Walk-in customer - insert NULL for customer ID
				$qry2 = "INSERT INTO sales(c_id, user_id) VALUES (NULL, '$eid')";
				echo "Query being executed (Walk-in): $qry2<br>";
			} else {
				// Registered customer
				$qry2 = "INSERT INTO sales(c_id, user_id) VALUES ('$cid', '$eid')";
				echo "Query being executed (Registered): $qry2<br>";
			}
			
			if(!($result2 = $conn->query($qry2))) {
				echo "<p style='color:red;'>MySQL Error: " . mysqli_error($conn) . "</p>";
			} else {
				$new_sale_id = mysqli_insert_id($conn);
				echo "<p style='color:green;'>SUCCESS! New Sale ID: " . $new_sale_id . "</p>";
				if($cid == 0) {
					echo "<p style='color:blue;'>Sale created for Walk-in Customer</p>";
				} else {
					echo "<p style='color:blue;'>Sale created for Customer ID: $cid</p>";
				}
			}
			echo "</div>";
		}
		

	?>
			
		<form method="post">
			<select id="med" name="med">
			<option value="0" selected="selected">Select Medicine</option>
					
					
	<?php	
		$qry3 = "SELECT med_name FROM meds";
		$result3 = $conn->query($qry3);
		echo mysqli_error($conn);
		if ($result3->num_rows > 0) {
			while($row4 = $result3->fetch_assoc()) {
				
				echo "<option>".$row4["med_name"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="search" value="Search">
	</form>
	
	<br><br><br>
	</center>
	

	<?php
	
		if(isset($_POST['search']) && !empty($_POST['med'])) {
			
					$med = $_POST['med'];
					$qry4 = "SELECT * FROM meds where med_name='$med'";
					$result4 = $conn->query($qry4); 
					$row4 = $result4->fetch_row();
				
			}
	?>
	
			<div class="one row" style="margin-right:160px;">
			<form method="post">
					<div class="column">
					
					<label for="medid">Medicine ID:</label>
					<input type="number" name="medid" value="<?php echo $row4[0]; ?>" readonly ><br><br>
					
					<label for="mdname">Medicine Name:</label>
					<input type="text" name="mdname" value="<?php echo $row4[1]; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mcat">Category:</label>
					<input type="text" name="mcat" value="<?php echo $row4[3]; ?>" readonly><br><br>
					
					<label for="mloc">Location:</label>
					<input type="text" name="mloc" value="<?php echo $row4[5]; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mqty">Quantity Available:</label>
					<input type="number" name="mqty" value="<?php echo $row4[2]; ?>" readonly><br><br>
					
					<label for="mprice">Price of One Unit:</label>
					<input type="number" name="mprice" value="<?php echo $row4[4]; ?>" readonly><br><br>
					
					</div>
					<label for="mcqty">Quantity Required:</label>
					<input type="number" name="mcqty">
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="add" value="Add Medicine">&nbsp;&nbsp;&nbsp;
	
		<?php
		
		if(isset($_POST['add'])) {
			
					$qry5 = "select sale_id from sales ORDER BY sale_id DESC LIMIT 1";
					$result5 = $conn->query($qry5); 
					$row5 = $result5->fetch_row();
					$sid = $row5[0];
					echo mysqli_error($conn);
			
					$mid = $_POST['medid'];
					$aqty = $_POST['mqty'];
					$qty = $_POST['mcqty'];
					
					if($qty > $aqty || $qty == 0) {
						echo "QUANTITY INVALID!";
					} else {
						$price = $_POST['mprice'] * $qty;
						$qry6 = "INSERT INTO sales_items(`sale_id`, `med_id`, `sale_qty`, `tot_price`) VALUES($sid, $mid, $qty, $price)";
						$result6 = mysqli_query($conn, $qry6);
						echo mysqli_error($conn);
						
						echo "<br><br> <center>";
						echo "<a class='button1 view-btn' href=pharm-pos2.php?sid=".$sid.">View Order</a>";
						echo "</center>";
					}
		}	
		?>

		
		</form>
	</div>
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