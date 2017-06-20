<?php

include 'utilities.php';
debugEchoPOST();debugEchoGET();
// Perform specified function as needed

// **************************************************
// * Creating a new invoice date (along with the appropriate number of slots)
if (isset($_POST['CreateInvoiceDate'])) {
	// Validate date first!
	// Get the date from POST and append zero hour marks (this feels awful)
	$validateDate = makeString($_POST['appDate'] . " 00:00:00");
	
	
	// Generate our sql query with the new date to see if it exists already
	$sql = "SELECT visitDate
			FROM Invoice
			WHERE visitDate=" . $validateDate;
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$invoiceInfo = queryDB($conn, $sql);
	closeDB($conn);
	
	// If the date already exists, return to the previous page
	if ($invoiceInfo->num_rows > 0) {
		// Set a cookie to tell the user the date exists
		// Done this way instead of with url params so reloading the page doesn't cause the warning
		createCookie("badAppDate", 1, 30);
		header("location: /RUMCPantry/ap_ao2.html");
	}
	else {
		// Find the available client ID
		$availID = getAvailableClient();
		
		// Generate the SQL statement to insert all of the invoices
		// There is only one database call
		$sql = "INSERT INTO Invoice (visitDate, visitTime, clientID, Status) VALUES ";
		$firstInsert = TRUE;
		foreach ($_POST["time"] as $timeSlot) {
			foreach ($_POST["qty"] as $i) {
				$sql .= (!$firstInsert ? "," : "");
				$firstInsert = FALSE;

				$sql .= "( " . $validateDate . ", '" . $timeSlot . "', $availID, 0)";
			}
		}
		// perform insertion
		$conn = createPantryDatabaseConnection();
		
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			echo "<h2>Insertion Successful</h2>";
			echo "<h3>" . $_POST['appDate'] . "</h3>";
			// go to view page with date parameter
			createCookie("newAppt", 1, 30);
			header("location: /RUMCPantry/ap_ao3.html?date=" . $_POST['appDate']);
			
		} 
		else {
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to insert invoices.", "red" );	
		}
		
		
		// **********************************
		echo ("<br><h1>New appointment date</h1><br><br>");
		closeDB($conn);
	}
}
elseif (isset($_POST['CreateInvoiceTimeSlot'])) {
	// Validate date first!
	// Get the date from POST and append zero hour marks (this feels awful)
	$validateDate = makeString($_POST['appDate'] . " 00:00:00");
	$timeSlot = $_POST['time'];
	
	$availID = getAvailableClient();
		
	// Generate the SQL statement to insert all of the invoices
	// There is only one database call
	$sql = "INSERT INTO Invoice (visitDate, visitTime, clientID, Status) VALUES ";
	$firstInsert = TRUE;
	
	for ($i = 0; $i < $_POST['qty'] ; $i++) {
		$sql .= (!$firstInsert ? "," : "");
		$firstInsert = FALSE;
		
		$sql .= "( " . $validateDate . ", '" . $timeSlot . "', $availID, 0)";
	}
	// perform insertion
	$conn = createPantryDatabaseConnection();
	
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		echo "<h2>Insertion Successful</h2>";
		echo "<h3>" . $_POST['appDate'] . "</h3>";
		// go to view page with date parameter
		createCookie("newTimeSlots", 1, 30);
		header("location: /RUMCPantry/ap_ao3.html?date=" . $_POST['appDate']);
		
	} 
	else {
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to insert invoices.", "red" );	
	}
	
	
	// **********************************
	echo ("<br><h1>New appointment date</h1><br><br>");
	closeDB($conn);
}
elseif (isset($_POST['DeleteInvoice'])) {
	// Generate our sql query to delete the invoice date in question
	$sql = "DELETE FROM Invoice
			WHERE invoiceID=" . $_POST['invoiceID'];
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if (queryDB($conn, $sql) === TRUE) {
		// Close the Database
		closeDB($conn);
		
		// Go back to the previous page main admin client ops page
		header ("location: /RUMCPantry/ap_ao3.html?date=" . $_POST['returnDate']);
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
}
else {
	echo "<h1>Nothing was set</h1><br>";
	debugEchoPOST();debugEchoGET();
	//header("location: /RUMCPantry/mainpage.html");
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>