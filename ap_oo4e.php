<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
<style>
th {
  padding:3px 5px 3px 5px;
}
</style>
	<h3 class="hide_for_print">Edit Order</h3>

	<div class="body-content">
	<?php

	// Get Vars: invoiceID | name | visitTime | familySize

	$invoiceID = ((isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0);
	$name = ((isset($_GET['name'])) ? $_GET['name'] : null);
	$visitTime = ((isset($_GET['visitTime'])) ? $_GET['visitTime'] : 0);
	$familySize = ((isset($_GET['familySize'])) ? $_GET['familySize'] : 0);

	// Create our query to get the invoice data
	if ( $name != null ) {
		
		//Connect to database
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		//Get invoice total
		$sqlttl = "SELECT SUM(totalitemsprice) as Itotal
				FROM invoicedescription 
				WHERE invoiceID=" . $invoiceID . "";
				
		$invttlData = queryDB($conn, $sqlttl);
		if ($invttlData == NULL || $invttlData->num_rows <= 0) {
			echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		$row = sqlfetch($invttlData);
		$invtotal = $row['Itotal']; 
		
		//Get all of the items on the invoice 	
		$sql = "SELECT I.name as iName, I.quantity as iQty, I.rack as rack, I.shelf as shelf, I.aisle as aisle, I.cName, I.invoiceDescID
				FROM invoice
				JOIN (SELECT item.itemName as name, quantity, invoicedescription.invoiceID as IinvoiceID, category.name as cName,
						rack, shelf, aisle, invoiceDescID
					  FROM invoicedescription
					  JOIN item
					  ON item.itemID=invoicedescription.itemID
					  JOIN category
					  ON item.categoryID=category.categoryID
					  WHERE invoicedescription.invoiceID=" . $invoiceID . ") as I
				ON I.IinvoiceID=invoice.invoiceID
				WHERE invoiceID=" . $invoiceID . "
				ORDER BY cName, aisle, rack, shelf, iName";
		$invoiceData = queryDB($conn, $sql);

		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			//echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		
    
    $sql = "SELECT visitDate FROM invoice where invoiceID = " . $invoiceID;
    $dateInfo = runQueryForOne($conn, $sql);
	
    closeDB($conn);
    ?>
    <table class="table">
      <thead><tr>
        <th>Client</th>
        <th>Appointment Date</th>
        <th>Family Size</th>
        <th>Invoice Total</th>
      </tr></thead>
      <tbody>
        <tr>
          <td><?=$name?></td>
          <td><?=date("F dS, y", strtotime($dateInfo['visitDate']))?> - <?=returnTime($visitTime)?></td>
          <td><?=$familySize?> (<?=familySizeDecoder($familySize)?>)</td>
          <td>$<?=number_format($invtotal,2)?></td>
        </tr>
      </tbody>
    </table>
    
    <?php
    
		// Print button
		echo "<button id='btn-print' onClick='window.print()'>Print</button>";

		// Loop through our data and spit out the data into our table
		echo "<table id='orderTable'><tr><th>Category</th><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th class='hide_for_print'></th></tr>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr><td>" . $invoice['cName'] . "</td>";
			echo "<td>" . $invoice['iName'] . "</td>";
			echo "<td>" . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td><td>" . rackDecoder($invoice['rack']) . "</td><td>" . shelfDecoder($invoice['shelf']) . "</td>";

			//echo "<td><input value=' ' class='btn_trash' name='RemoveItem' ";
        echo "<td class='hide_for_print'><button type='submit' class='btn-icon' name='RemoveItem' ";
        echo "onclick='AJAX_RemoveFromInvoice(this)'>";
        echo "<i class='fa fa-trash'></i></button></td></tr>";
        
        
			//echo "<td><input value=' ' name=" . $invoice['invoiceDescID'] . " class='btn_trash' name='RemoveItem' ";
			//echo "type='submit' onclick='AJAX_RemoveFromInvoice(this)'></td></tr>";

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
		echo "<input class='btn-nav btn-nav-sm' type='submit' name='addItemToOrder' value='Add to Invoice'>";
		echo "</form>";
		echo "</div>"; // /hide_for_print


	}
	else {
		echo "Something went wrong, please go back and try again.";
	}

	echo "<div id='ErrorLog'></div>";

  include 'php/footer.php'; ?>
<script src="js/orderFormOps.js"></script>