<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="nav2.css">
  <link rel="stylesheet" type="text/css" href="form4.css">
  <title>Add Employee</title>
</head>
<body>

<div class="sidenav">
  <h2 style="font-family:Arial; color:white; text-align:center;">Zam Zam Pharmacy</h2>
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
    <h2>ADD EMPLOYEE DETAILS</h2>
  </div>
</center>

<div class="one row">
<?php
include "config.php";

if (isset($_POST['add'])) {
  $username = $_POST['username'];
  $password = $_POST['password']; // In production, hash this
  $fname = $_POST['efname'];
  $lname = $_POST['elname'];
  $bdate = $_POST['ebdate'];
  $age = intval($_POST['eage']);
  $sex = $_POST['esex'];
  $etype = $_POST['etype'];
  $jdate = $_POST['ejdate'];
  $sal = floatval($_POST['esal']);
  $phno = $_POST['ephno'];
  $mail = $_POST['e_mail'];
  $add = $_POST['eadd'];

  $sql = "INSERT INTO users (username, password, email, role, first_name, last_name, birth_date, age, gender, join_date, salary, phone, address) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssissdss", $username, $password, $mail, $etype, $fname, $lname, $bdate, $age, $sex, $jdate, $sal, $phno, $add);

  if ($stmt->execute()) {
    echo "<p style='color:green;'>Employee successfully added!</p>";
  } else {
    echo "<p style='color:red;'>Error! Please check your input.</p>";
  }

  $stmt->close();
  $conn->close();
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
  <div class="column">
    <p><label>Username:</label><br><input type="text" name="username" required></p>
    <p><label>Password:</label><br><input type="password" name="password" required></p>
    <p><label>First Name:</label><br><input type="text" name="efname" required></p>
    <p><label>Last Name:</label><br><input type="text" name="elname" required></p>
    <p><label>Date of Birth:</label><br><input type="date" name="ebdate" required></p>
    <p><label>Age:</label><br><input type="number" name="eage" min="18" max="100" required></p>
    <p>
      <label>Sex:</label><br>
      <select name="esex" required>
        <option value="" disabled selected>Select</option>
        <option value="Female">Female</option>
        <option value="Male">Male</option>
        <option value="Others">Others</option>
      </select>
    </p>
  </div>

  <div class="column">
    <p>
      <label>Employee Type:</label><br>
      <select name="etype" required>
        <option value="" disabled selected>Select</option>
        <option value="pharmacist">Pharmacist</option>
        <option value="admin">Admin</option>
      </select>
    </p>
    <p><label>Date of Joining:</label><br><input type="date" name="ejdate" required></p>
    <p><label>Salary:</label><br><input type="number" step="0.01" name="esal" required></p>
    <p><label>Phone Number:</label><br><input type="tel" pattern="[0-9]{10,15}" name="ephno" required></p>
    <p><label>Email ID:</label><br><input type="email" name="e_mail" required></p>
    <p><label>Address:</label><br><input type="text" name="eadd" required></p>
  </div>

  <input type="submit" name="add" value="Add Employee">
</form>
<br>
</div>

<script>
  var dropdown = document.getElementsByClassName("dropdown-btn");
  for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
      this.classList.toggle("active");
      var dropdownContent = this.nextElementSibling;
      dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
  }
</script>

</body>
</html>
