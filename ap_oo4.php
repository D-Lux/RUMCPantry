<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/orderFormOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<?php include 'php/utilities.php'; ?>
    <title>View Order</title>
	
</head>

<body>

    <button onclick="goBack()">Go Back</button>
	<br><br>
	
	
	<?php 
	session_start();
	$invoiceID = 0;
	$name = null;
	$visitTime = 0;
	$familySize = 0;
	if ( ( isset($_POST['invoiceID'] )) &&
		 ( isset($_POST['name'] )) &&
		 ( isset($_POST['visitTime'] )) &&
		 ( isset($_POST['familySize'] ))) {
		$invoiceID = $_POST['invoiceID'];
		$name = $_POST['name'] ;
		$visitTime = $_POST['visitTime'];
		$familySize = $_POST['familySize'];
	}
	elseif ( ( isset($_SESSION['viewInvoice_invoiceID'] )) &&
			 ( isset($_SESSION['viewInvoice_name'] )) &&
			 ( isset($_SESSION['viewInvoice_visitTime'] )) &&
			 ( isset($_SESSION['viewInvoice_familySize'] ))) {
		$invoiceID = $_SESSION['viewInvoice_invoiceID'];
		$name = $_SESSION['viewInvoice_name'] ;
		$visitTime = $_SESSION['viewInvoice_visitTime'];
		$familySize = $_SESSION['viewInvoice_familySize'];
	}
	
	
	//debugEchoPOST();
	// Post Vars: invoiceID | name | visitTime | familySize
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
		
		// Loop through our data and spit out the data
		echo "<h4>Client: " . $name . " | Appointment Time: " . returnTime($visitTime);
		echo " | Family Size: " . familySizeDecoder($familySize) . "</h4>";
		
		echo "<table id='orderTable'><tr><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th></th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td><td>" . $invoice['rack'] . "</td><td>" . $invoice['shelf'] . "</td>";
			
			echo "<td><input value=' ' name=" . $invoice['invoiceDescID'] . " class='btn_trash' name='RemoveItem' ";
			echo "type='submit' onclick='AJAX_RemoveFromInvoice(this)'></td></tr>";	

			//echo aisleDecoder($invoice['aisle']) . "-" . $invoice['rack'] . "-" . $invoice['shelf'];
			//echo "| (" . $invoice['iQty'] . ") " . $invoice['iName'] . "<br>";
		}
		echo "</table>";
		echo "<br><br>";
		
		echo "<button onClick='window.print()'>Print</button>";
		echo "<br><br>";
		
		// If we came from the checkin page, allow 'mark as processed'
		if ( (isset($_POST['viewInvoice']) ) && (isset($_POST['status'])) ) {
			echo "<form method='post' action='php/orderOps.php'>";
			echo "<input type='hidden' name='invoiceID' value=" . $invoiceID . ">";
			echo "<input type='hidden' name='status' value=" . $_POST['status'] . ">";
			echo "<input type='submit' name='SetInvoiceProcessed' value='Mark as Processed'>";
			echo "</form>";
		}
		
		// Add an item
		echo "<br><br>";
		echo "<form method='post' action='php/orderOps.php' onSubmit='return validateAddItemToInvoice()'>";
		echo "<input type='hidden' name='invoiceID' value=" . $invoiceID . ">";
		echo "<input type='hidden' name='name' value='" . $name . "'>";
		echo "<input type='hidden' name='visitTime' value='" . $visitTime . "'>";
		echo "<input type='hidden' name='familySize' value='" . $familySize . "'>";
		
		echo "Item to Add: ";
		createDatalist_i('', 'itemNames', 'item', 'itemName', 'addItem', 1);
		echo "<br>Quantity: <input type='number' name='qty' min=1 value=1><br>";
		echo "<input type='submit' name='addItemToOrder' value='Add to Invoice'>";
		echo "</form>";
		
		
	}
	else {
		echo "Invoice timed out, please Go Back and reselect the invoice";
	}

		echo "<br><br><br>";
		echo "<div id='ErrorLog'></div>";
	?>
	

</body>

</html>