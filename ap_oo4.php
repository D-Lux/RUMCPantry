<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />

<!-- <h3>View Order</h3> -->
	
	<?php
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// Post Vars: invoiceID | name | visitTime | familySize
	$invoiceID = (isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0;
	
	$sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, 
				(Client.numOfKids + numOfAdults) AS familySize 
			FROM Invoice 
			JOIN Client 
			ON Invoice.clientID=Client.clientID 
			JOIN FamilyMember 
			ON Client.clientID=FamilyMember.clientID 
			WHERE Invoice.invoiceID = " . $invoiceID . " 
			AND FamilyMember.isHeadOfHousehold = true";
	
	$results    = returnAssocArray(queryDB($conn, $sql));
	$name       = current($results)['firstName'] . " " . current($results)['lastName'];
	$printName	= current($results)['lastName'];
	$visitTime  = current($results)['visitTime'];
	$familySize = current($results)['familySize'];
	
	
	
	
	if ( ($name != null) && ($invoiceID != 0) ){
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
		echo "<button id='btn-print' onClick='AJAX_SetInvoicePrinted(" . $invoiceID . ")'><i class='fa fa-print'></i> Print</button>";
		
		// Loop through our data and spit out the data into our table
		echo "<table id='orderTable'><tr><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td>";
			echo "<td>" . rackDecoder($invoice['rack']) . "</td>";
			echo "<td>" . shelfDecoder($invoice['shelf']) . "</td>";
			
			echo "</tr>";	
		}
		echo "</table>";


		//Print out name tags numLines times
		echo "<div id='nameTags' style='display:none;'><br><br><hr><h6>";
		$numLines = orderFormNameTagLength($familySize);
		for ($i = 0; $i < $numLines; $i++) {
			echo $printName . "&emsp;&emsp;" . $printName . "<br>";
		}
		echo "</h6></div>";
	}
	else {
		echo "Something went wrong, please go back and try again.";
	}
	
	echo "<div id='ErrorLog'></div>";
	?>

<?php include 'php/footer.php'; ?>
<script src="js/orderFormOps.js"></script>