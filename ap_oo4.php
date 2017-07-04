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
	// TODO: Finish this page
	$sql = "SELECT visitDate, visitTime, I.name as iName, I.quantity as iQty
			FROM Invoice
			JOIN (SELECT Item.itemName as name, quantity, invoiceID
				  FROM InvoiceDescription
				  JOIN Item
				  WHERE Item.itemID=InvoiceDescription.itemID) as I
			WHERE I.invoiceID=Invoice.invoiceID";
	
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$invoiceData = queryDB($conn, $sql);
	
	if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
		echo "error: " . mysqli_error($conn);
		echo "Failed to find invoice.";	
	}
	else {
		// Loop through our data and create a list of selectable active appointments
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<a href=ap_oo4.php?invoiceID=" . $invoice['invoiceID'] . ">";
			echo $invoice['ln'] . "</a> " . $invoice['visitTime'] . " " . visitStatusDecoder($invoice['status']);
			echo "<br>";
		}
	}

	
	?>
	

</body>

</html>