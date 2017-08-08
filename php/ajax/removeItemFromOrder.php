<?php
	include('../utilities.php');
	
	// AJAX call to remove an item from an order
	if (isset($_GET['invoiceDescID'])) {
		
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			echo "ERROR - No database connection";
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "DELETE FROM invoiceDescription
				WHERE invoiceDescID=" . $_GET['invoiceDescID'];
		if (queryDB($conn, $sql) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
		}
		//echo "Query: " . $sql;
		
		closeDB($conn);
	}
?>