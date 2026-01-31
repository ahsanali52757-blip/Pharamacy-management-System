<?php
		$conn = mysqli_connect("localhost", "root", "", "hello");
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}
?>