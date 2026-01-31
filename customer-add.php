<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="nav2.css">
    <link rel="stylesheet" type="text/css" href="form4.css">
    <title>Customers</title>
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
            <h2>ADD CUSTOMER DETAILS</h2>
        </div>
    </center>

    <br><br><br><br><br><br><br><br>

    <div class="one">
        <div class="row">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="column">
                    <p>
                        <label for="cfname">First Name:</label><br>
                        <input type="text" name="cfname" required>
                    </p>
                    <p>
                        <label for="clname">Last Name:</label><br>
                        <input type="text" name="clname" required>
                    </p>
                    <p>
                        <label for="age">Age:</label><br>
                        <input type="number" name="age" required>
                    </p>

                    <p>
                        <label for="sex">Sex:</label><br>
                        <select id="sex" name="sex" required>
                            <option value="selected">Select</option>
                            <option>Female</option>
                            <option>Male</option>
                            <option>Others</option>
                        </select>
                    </p>
                </div>

                <div class="column">
                    <p>
                        <label for="phno">Phone Number:</label><br>
                        <input type="text" name="phno" required>
                    </p>
                    <p>
                        <label for="emid">Email ID:</label><br>
                        <input type="text" name="emid" required>
                    </p>
                </div>

                <input type="submit" name="add" value="Add Customer">
            </form>

            <br>

            <?php
            include "config.php";

            if (isset($_POST['add'])) {
                // Escape user input to prevent SQL injection
                $fname = mysqli_real_escape_string($conn, $_POST['cfname']);
                $lname = mysqli_real_escape_string($conn, $_POST['clname']);
                $age = mysqli_real_escape_string($conn, $_POST['age']);
                $sex = mysqli_real_escape_string($conn, $_POST['sex']);
                $phno = mysqli_real_escape_string($conn, $_POST['phno']);
                $mail = mysqli_real_escape_string($conn, $_POST['emid']);

                // Insert query, omitting C_ID since it auto-increments
                $sql = "INSERT INTO customer (C_FNAME, C_LNAME, C_AGE, C_SEX, C_PHNO, C_MAIL) 
                        VALUES ('$fname', '$lname', $age, '$sex', '$phno', '$mail')";

                if (mysqli_query($conn, $sql)) {
                    echo "<p style='font-size: 14px;'>Customer successfully added!</p>";
                } else {
                    echo "<p style='font-size: 14px; color:red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            $conn->close();
            ?>
        </div>
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
