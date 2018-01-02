<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
<script src="js/orderFormOps.js"></script>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />

	<h3>Edit Order</h3>
	
	<div class="body_content">
	<?php
	
	// Get Vars: invoiceID | name | visitTime | familySize
	
	$invoiceID = ((isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0);
	$name = ((isset($_GET['name'])) ? $_GET['name'] : null);
	$visitTime = ((isset($_GET['visitTime'])) ? $_GET['visitTime'] : 0);
	$familySize = ((isset($_GET['familySize'])) ? $_GET['familySize'] : 0);
	
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
			//echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		closeDB($conn);
		
		// Loop through our data and spit out the data into our table
		echo "<h4>Client: " . $name . " | Appointment Time: " . returnTime($visitTime);
		echo " | Family Size: " . familySizeDecoder($familySize) . "</h4>";
		
		// Print button
		echo "<button id='btn_print' onClick='window.print()'>Print</button>";
		
		// Order information
		echo "<table id='orderTable'><tr><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th></th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td><td>" . $invoice['rack'] . "</td><td>" . $invoice['shelf'] . "</td>";
			
			echo "<td><input value=' ' name=" . $invoice['invoiceDescID'] . " class='btn_trash' name='RemoveItem' ";
			echo "type='submit' onclick='AJAX_RemoveFromInvoice(this)'></td></tr>";	

		}
		echo "</table><br>";
		
		// Add an item
		echo "<div id='hide_for_print'>";
		echo "<form method='post' action='php/orderOps.php' onSubmit='return validateAddItemToInvoice()'>";
		echo "<input type='hidden' name='invoiceID' value=" . $invoiceID . ">";
		echo "<input type='hidden' name='name' value='" . $name . "'>";
		echo "<input type='hidden' name='visitTime' value='" . $visitTime . "'>";
		echo "<input type='hidden' name='familySize' value='" . $familySize . "'>";
		
		echo "Item to Add:";
		createDatalist_i('', 'itemNames', 'item', 'itemName', 'addItem', 1);
		echo "<div style='display: inline-block; margin-left: 8px;'>Quantity:<input type='number' id='addQty' name='qty' value=1></div><br>";
		echo "<input type='submit' name='addItemToOrder' value='Add to Invoice'>";
		echo "</form>";
		echo "</div>"; // /hide_for_print
		
		
	}
	else {
		echo "Something went wrong, please go back and try again.";
	}
	
	echo "<div id='ErrorLog'></div>";
	?>
	

	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>

</html>