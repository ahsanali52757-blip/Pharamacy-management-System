<?php
include "config.php";
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="table1.css">
  <link rel="stylesheet" type="text/css" href="nav2.css">
  <title>Medicines</title>
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
      <h2> MEDICINE INVENTORY </h2>
      <!-- Search bar -->
<div style="text-align:center; margin-bottom: 30px;">
  <form method="GET" style="display: inline-block;">
    <input 
      type="text" 
      name="search" 
      placeholder="Search Medicine Name or Category" 
      value="<?php if (isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>" 
      style="padding: 8px; width: 300px; border-radius: 5px; border: 1px solid #ccc;">
    <button 
      type="submit" 
      style="padding: 8px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">
      Search
    </button>
  </form>
</div>

    </div>

    <!-- Search bar -->
    <form method="GET" style="margin-bottom: 20px;">
      <input type="text" name="search" placeholder="Search Medicine Name or Category"
        value="<?php if (isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>">
      <button type="submit">Search</button>
    </form>
  </center>

  <table align="right" id="table1" style="margin-right:100px;">
    <tr>
      <th>Medicine ID</th>
      <th>Medicine Name</th>
      <th>Quantity Available</th>
      <th>Category</th>
      <th>Price</th>
      <th>Location in Store</th>
      <th>Action</th>
    </tr>

    <?php
    $searchTerm = "";

    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
      $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
      $sql = "SELECT * FROM meds WHERE MED_NAME LIKE '%$searchTerm%' OR CATEGORY LIKE '%$searchTerm%'";
    } else {
      $sql = "SELECT * FROM meds";
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["MED_ID"] . "</td>";
        echo "<td>" . $row["MED_NAME"] . "</td>";
        echo "<td>" . $row["MED_QTY"] . "</td>";
        echo "<td>" . $row["CATEGORY"] . "</td>";
        echo "<td>" . $row["MED_PRICE"] . "</td>";
        echo "<td>" . $row["LOCATION_RACK"] . "</td>";
        echo "<td align='center'>";
        echo "<a class='button1 edit-btn' href='inventory-update.php?id=" . $row['MED_ID'] . "'>Edit</a> ";
        echo "<a class='button1 del-btn' href='inventory-delete.php?id=" . $row['MED_ID'] . "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='7' style='text-align:center;'>No records found.</td></tr>";
    }

    $conn->close();
    ?>
  </table>

  <script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    for (var i = 0; i < dropdown.length; i++) {
      dropdown[i].addEventListener("click", function () {
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

</body>

</html>
