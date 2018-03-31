<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
<style>
th {
  padding:3px 5px 3px 5px;
}
</style>
	
	<?php
	$conn = connectDB();
	
	// Post Vars: invoiceID | name | visitTime | familySize
	$invoiceID = (isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0;
	
	$sql = "SELECT familymember.firstName, familymember.lastName, invoice.visitTime, invoice.status,
				(client.numOfKids + numOfAdults) AS familySize 
			FROM invoice 
			JOIN client 
			ON invoice.clientID=client.clientID 
			JOIN familymember
			ON client.clientID=familymember.clientID 
			WHERE invoice.invoiceID = " . $invoiceID . " 
			AND familymember.isHeadOfHousehold = true";
	
	$results    = runQueryForOne($conn, $sql);
	$name       = $results['firstName'] . " " . $results['lastName'];
	$printName	= $results['lastName'];
	$visitTime  = $results['visitTime'];
	$familySize = $results['familySize'];
  $LeanneNum  = ($results['status'] % 100) + 1;
	
	
	if ( ($name != null) && ($invoiceID != 0) ){
		// Create our query to get the invoice data
		$sql = "SELECT I.name as iName, I.quantity as iQty, I.rack as rack, I.shelf as shelf, I.aisle as aisle
				FROM invoice
				JOIN (SELECT item.itemName as name, quantity, invoicedescription.invoiceID as IinvoiceID, 
						rack, shelf, aisle
					  FROM invoicedescription
					  JOIN item
					  ON item.itemID=invoicedescription.itemID
					  WHERE invoicedescription.invoiceID=" . $invoiceID . ") as I
				ON I.IinvoiceID=invoice.invoiceID
				WHERE invoiceID=" . $invoiceID . "
				ORDER BY aisle, rack, shelf, iName";
				
		$invoiceData = queryDB($conn, $sql);
		
		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		closeDB($conn);
		
		// Show basic client information
		echo "<table><tr><th>Client</th><th>Time</th><th>Number</th><th>Size</th></tr>";
		echo "<tr><td>" . $name . "</td><td>" . returnTime($visitTime) . "</td><td>" . $LeanneNum;
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