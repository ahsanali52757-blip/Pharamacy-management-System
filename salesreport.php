<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="table1.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<title>Reports</title>
<style>
body { font-family: Arial; }
</style>
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
    <h2> TRANSACTION REPORTS</h2>
</div>
</center>

<br><br><br><br><br><br><br><br><br>

<?php include "config.php"; ?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" style="margin-left:300px;">
    <p>
        <label for="start">Start Date:</label>
        <input type="date" name="start" required>
    </p>
    <p>
        <label for="end">End Date:</label>
        <input type="date" name="end" required>
    </p>
    <p>
        <label for="employee">Select Employee:</label>
        <select name="employee">
            <option value="">All Employees</option>
            <?php
            $empResult = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role IN ('admin', 'pharmacist')");
            while ($emp = mysqli_fetch_assoc($empResult)) {
                $fullName = $emp['first_name'] . " " . $emp['last_name'];
                $selected = (isset($_POST['employee']) && $_POST['employee'] == $emp['user_id']) ? "selected" : "";
                echo "<option value='{$emp['user_id']}' $selected>$fullName</option>";
            }
            ?>
        </select>
    </p>
    <input type="submit" name="submit" value="View Records">
</form>

<?php
if (isset($_POST['submit'])) {

    $start = $_POST['start'];
    $end = $_POST['end'];
    $employee = $_POST['employee'] ?? '';

    // Fetch purchase amount (for all)
    $resPurchase = mysqli_query($conn, "SELECT P_AMT('$start','$end') AS PAMT") or die(mysqli_error($conn));
    $rowPurchase = mysqli_fetch_assoc($resPurchase);
    $pamt = $rowPurchase['PAMT'] ?? 0;

    // Fetch sales amount with optional employee filter
    if (!empty($employee)) {
        $resSalesAmt = mysqli_query($conn, "SELECT SUM(total_amt) AS SAMT FROM sales WHERE s_date >= '$start' AND s_date <= '$end' AND user_id = '$employee'") or die(mysqli_error($conn));
    } else {
        $resSalesAmt = mysqli_query($conn, "SELECT SUM(total_amt) AS SAMT FROM sales WHERE s_date >= '$start' AND s_date <= '$end'") or die(mysqli_error($conn));
    }
    $salesAmtRow = mysqli_fetch_assoc($resSalesAmt);
    $samt = $salesAmtRow['SAMT'] ?? 0;

    $profit = $samt - $pamt;
    $profits = number_format($profit, 2);
?>

<!-- PURCHASE TABLE -->
<table align="right" id="table1" style="margin-right:100px;">
    <tr>
        <th>Purchase ID</th>
        <th>Supplier ID</th>
        <th>Medicine ID</th>
        <th>Quantity</th>
        <th>Date of Purchase</th>
        <th>Cost of Purchase (Rs)</th>
    </tr>
<?php
$sqlPurchase = "SELECT p_id, sup_id, med_id, p_qty, p_cost, pur_date FROM purchase 
        WHERE pur_date >= '$start' AND pur_date <= '$end'";
$resultPurchase = $conn->query($sqlPurchase);
if ($resultPurchase->num_rows > 0) {
    while($row = $resultPurchase->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["p_id"] . "</td>";
        echo "<td>" . $row["sup_id"] . "</td>";
        echo "<td>" . $row["med_id"] . "</td>";
        echo "<td>" . $row["p_qty"] . "</td>";
        echo "<td>" . $row["pur_date"] . "</td>";
        echo "<td>" . $row["p_cost"] . "</td>";
        echo "</tr>";
    }
}
echo "<tr><td colspan=5>Total</td><td>Rs." . $pamt . "</td></tr>";
echo "</table>";
?>

<!-- SALES TABLE -->
<table align="right" id="table1" style="margin-right:100px;">
    <tr>
        <th>Sale ID</th>
        <th>Customer ID</th>
        <th>Employee Name</th>
        <th>Date</th>
        <th>Sale Amount (Rs)</th>
    </tr>
<?php
$sqlSales = "SELECT s.sale_id, s.c_id, s.s_date, s.total_amt, u.first_name, u.last_name
            FROM sales s
            JOIN users u ON s.user_id = u.user_id
            WHERE s.s_date >= '$start' AND s.s_date <= '$end'";
if (!empty($employee)) {
    $sqlSales .= " AND s.user_id = '$employee'";
}
$resultSales = $conn->query($sqlSales);
if ($resultSales->num_rows > 0) {
    while($row = $resultSales->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["sale_id"] . "</td>";
        echo "<td>" . $row["c_id"] . "</td>";
        echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
        echo "<td>" . $row["s_date"] . "</td>";
        echo "<td>" . $row["total_amt"] . "</td>";
        echo "</tr>";
    }
}
echo "<tr><td colspan=4>Total</td><td>Rs." . $samt . "</td></tr>";
echo "</table>";
?>

<!-- PROFIT TABLE -->
<table align="right" id="table1" style="margin-bottom:100px; margin-right:100px;">
    <tr style="background-color: #f2f2f2;">
        <td>Transaction Profit</td>
        <td>Rs.<?php echo $profits; ?></td>
    </tr>
</table>

<?php } ?>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
}
</script>

</body>
</html>
