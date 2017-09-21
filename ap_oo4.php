<!DOCTYPE html>

<html>
	<head>
		<title>Roselle UMC</title>
		<style>
			#btn_back, .btn_view {
				margin: 0 300px 0px 0px;
				float: right;
				height: 33px;
				font-size: 1.1rem;
				border-radius: 25px;
				border: solid 2px #AAA;
				cursor: pointer;
			}

			#btn_back:active, .btn_view:active {
				border: solid 2px #888;
				background-color: #AAA;
			}
		</style>
	</head>
	<body>

<?php include('php/utilities.php'); ?>
<script src="js/orderFormOps.js"></script>
<script src="js/utilities.js"></script>
<button id='btn_back' onclick="goBack()">Back</button>

<h3>View Order</h3>
	
	<?php
	// TODO: go to checkin on back button press
	// TODO: Set order status to printed (if it is already ready to print)
	// Post Vars: invoiceID | name | visitTime | familySize
	$invoiceID = ((isset($_POST['invoiceID'])) ? $_POST['invoiceID'] : 0);
	$name = ((isset($_POST['name'])) ? $_POST['name'] : null);
	$visitTime = ((isset($_POST['visitTime'])) ? $_POST['visitTime'] : 0);
	$familySize = ((isset($_POST['familySize'])) ? $_POST['familySize'] : 0);
	
	// Create our query to get the invoice data
	if ( $name != null ) {
		$sql = "SELECT I.name as iName, I.quantity as iQty, I.invoiceDescID as invoiceDescID,
					I.rack as rack, I.shelf as shelf, I.aisle as aisle
				FROM Invoice
				JOIN (SELECT Item.itemName as name, quantity, invoiceDescID, 
						InvoiceDescription.invoiceID as IinvoiceID, rack, shelf, aisle
					  FROM InvoiceDescription
					  JOIN Item
					  ON Item.itemID=InvoiceDescription.itemID
					  WHERE InvoiceDescription.invoiceID=" . $invoiceID . ") as I
				ON I.IinvoiceID=Invoice.invoiceID
				WHERE invoiceID=" . $invoiceID . "
				ORDER BY aisle, rack, shelf, iName";
		
		$conn = createPantryDatabaseConnection();
		
		// Check fail conditions
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$invoiceData = queryDB($conn, $sql);
		
		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		closeDB($conn);
		
		// Loop through our data and spit out the data into our table
		echo "<h4>Client: " . $name . " | Appointment Time: " . returnTime($visitTime);
		echo " | Family Size: " . familySizeDecoder($familySize) . "</h4>";
		
		// Print button
		echo "<button class='btn_view' onClick='window.print()'>Print</button>";
		
		echo "<table id='orderTable'><tr><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td><td>" . $invoice['rack'] . "</td><td>" . $invoice['shelf'] . "</td>";
			
			echo "</tr>";	
		}
		echo "</table>";
			
		// If we came from the checkin page, allow 'mark as processed'
		if ( (isset($_POST['viewInvoice']) ) && (isset($_POST['status'])) ) {
			echo "<form method='post' action='php/orderOps.php'>";
			echo "<input type='hidden' name='invoiceID' value=" . $invoiceID . ">";
			echo "<input type='hidden' name='status' value=" . $_POST['status'] . ">";
			echo "<input class='btn_view' type='submit' name='SetInvoiceProcessed' value='Mark as Processed'>";
			echo "</form>";
		}
		
		echo "<br><br><hr>";
		//Print out name tags numNames times
		$numNames = 2;
		for ($i = 0; $i < $numNames; $i++) {
			echo "<h1>" .  $name . "</h1>";
		}
	}
	else {
		echo "Something went wrong, please go back and try again.";
	}
	
	echo "<div id='ErrorLog'></div>";
	?>

</body>

</html>