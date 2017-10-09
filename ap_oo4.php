
<?php include 'php/header.php'; ?>
<script src="js/orderFormOps.js"></script>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
<button id='btn_back' onclick="goBack()">Back</button>

<!-- <h3>View Order</h3> -->
	
	<?php
	// Post Vars: invoiceID | name | visitTime | familySize
	$invoiceID = ((isset($_POST['invoiceID'])) ? $_POST['invoiceID'] : 0);
	$name = ((isset($_POST['name'])) ? $_POST['name'] : null);
	$visitTime = ((isset($_POST['visitTime'])) ? $_POST['visitTime'] : 0);
	$familySize = ((isset($_POST['familySize'])) ? $_POST['familySize'] : 0);
	
	
	if ( ($name != null) && ($invoiceID != 0) ){
		// Connect to the database
		$conn = createPantryDatabaseConnection();
		
		// Check fail conditions
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query to get the invoice data
		$sql = "SELECT I.name as iName, I.quantity as iQty, I.rack as rack, I.shelf as shelf, I.aisle as aisle
				FROM Invoice
				JOIN (SELECT Item.itemName as name, quantity, InvoiceDescription.invoiceID as IinvoiceID, 
						rack, shelf, aisle
					  FROM InvoiceDescription
					  JOIN Item
					  ON Item.itemID=InvoiceDescription.itemID
					  WHERE InvoiceDescription.invoiceID=" . $invoiceID . ") as I
				ON I.IinvoiceID=Invoice.invoiceID
				WHERE invoiceID=" . $invoiceID . "
				ORDER BY aisle, rack, shelf, iName";
				
		$invoiceData = queryDB($conn, $sql);
		
		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		closeDB($conn);
		
		// Show basic client information
		echo "<table><tr><th>Client</th><th>Time</th><th>Size</th></tr>";
		echo "<tr><td>" . $name . "</td><td>" . returnTime($visitTime);
		echo "</td><td>" . familySizeDecoder($familySize) . "</td></tr></table><br>";
		
		// Print button
		echo "<button id='btn_print' onClick='AJAX_SetInvoicePrinted(" . $invoiceID . ")'>Print</button>";
		
		// Loop through our data and spit out the data into our table
		echo "<table id='orderTable'><tr><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td><td>" . $invoice['rack'] . "</td><td>" . $invoice['shelf'] . "</td>";
			
			echo "</tr>";	
		}
		echo "</table>";


		//Print out name tags numNames times
		echo "<div id='nameTags' style='display:none;'><br><br><hr><h6>";
		$numLines = 4;
		for ($i = 0; $i < $numLines; $i++) {
			echo $name . "&emsp;&emsp;" . $name . "<br>";
		}
		echo "</h6></div>";
	}
	else {
		echo "Something went wrong, please go back and try again.";
	}
	
	echo "<div id='ErrorLog'></div>";
	?>

</body>

</html>