<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
    <title>View Active Orders</title>
	
</head>

<body>

    <button onclick="goBack()">Go Back</button>
	<br><br>
	
	<?php 
	//debugEchoPOST();
	if (isset($_POST['viewInvoice']) ) {
		// Post Vars: invoiceID | name | visitTime

		// TODO: Finish this page
		$sql = "SELECT I.name as iName, I.quantity as iQty
				FROM Invoice
				JOIN (SELECT Item.itemName as name, quantity, InvoiceDescription.invoiceID as IinvoiceID
					  FROM InvoiceDescription
					  JOIN Item
					  ON Item.itemID=InvoiceDescription.itemID
					  WHERE InvoiceDescription.invoiceID=" . $_POST['invoiceID'] . ") as I
				ON I.IinvoiceID=Invoice.invoiceID
				WHERE invoiceID=" . $_POST['invoiceID'];
		
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$invoiceData = queryDB($conn, $sql);
		
		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "error: " . mysqli_error($conn);
			echo "<br>Invoice is currently empty.";	
		}
		else {			
			// Loop through our data and spit out the data simply
			echo "<h4>Client: " . $_POST['name'] . " | Appointment Time: " . returnTime($_POST['visitTime']);
			echo " | Family Size: " . familySizeDecoder($_POST['familySize']) . "</h4>";
			while( $invoice = sqlFetch($invoiceData) ) {
				echo "(" . $invoice['iQty'] . ") " . $invoice['iName'] . "<br>";
			}
			
			echo "<br><br>";
			
			echo "<button onClick='window.print()'>Print</button>";
			echo "<br><br>";
			echo "<form method='post' action='php/orderOps.php'>";
			echo "<input type='hidden' name='invoiceID' value=" . $_POST['invoiceID'] . ">";
			echo "<input type='hidden' name='status' value=" . $_POST['status'] . ">";
			echo "<input type='submit' name='SetInvoiceProcessed' value='Mark as Processed'>";
			echo "</form>";
		}
	}
			

	?>
	

</body>

</html>