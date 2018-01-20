<?php
	include('../utilities.php');

	if (isset($_GET['invoiceID'])) {
		$invoiceID = $_GET['invoiceID'];
		
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Get the invoice status, if it's in the ready to be printed queue, advance it to the printed queue
		$sql = "SELECT status
				FROM Invoice
				WHERE invoiceID=" . $invoiceID . "
				LIMIT 1";
		// Run the query and get the data fetch
		$statusQuery = queryDB($conn, $sql);
		$statusData = sqlFetch($statusQuery);
		
		if (IsReadyToPrint($statusData['status'])) {
			$newStatus = AdvanceInvoiceStatus($statusData['status']);
			// If we are ready to print, advance to printed status
			$sql = "UPDATE Invoice
					SET status=" . $newStatus . "
					WHERE invoiceID=" . $invoiceID;
			queryDB($conn, $sql);
			
			echo "0";
		}
		else {
			echo "-1";
		}
		
		// Close the Database
		closeDB($conn);
	}
?>