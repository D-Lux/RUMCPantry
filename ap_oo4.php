<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 10;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />

<?php
	$conn = connectDB();

	// Post Vars: invoiceID | name | visitTime | familySize
	$invoiceID = (isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0;

	$sql = "SELECT familymember.firstName, familymember.lastName, invoice.visitTime, invoice.status,
				numOfKids, numOfAdults
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
	$numOfKids  = $results['numOfKids'];
    $numOfAdults= $results['numOfAdults'];
    $LeanneNum  = ($results['status'] % 100) + 1;
    
    $printFontSize = round(100 / (strlen($printName) * 2),2,PHP_ROUND_HALF_UP);
    //printFontSize = 20;


	if ( ($name != null) && ($invoiceID != 0) ){
		// Create our query to get the invoice data
		$sql = "SELECT I.name as iName, I.quantity as iQty, I.rack as rack, I.shelf as shelf, I.aisle as aisle, I.cName
				FROM invoice
				JOIN (SELECT item.itemName as name, quantity, invoicedescription.invoiceID as IinvoiceID, category.name as cName, category.formOrder
						rack, shelf, aisle
					  FROM invoicedescription
					  JOIN item
					  ON item.itemID=invoicedescription.itemID
					  JOIN category
					  ON item.categoryID=category.categoryID
					  WHERE invoicedescription.invoiceID=" . $invoiceID . ") as I
				ON I.IinvoiceID=invoice.invoiceID
				WHERE invoiceID=" . $invoiceID . "
				ORDER BY formOrder, aisle, rack, shelf, iName";

        $sql = "SELECT item.itemname as iName, invoicedescription.quantity as iQty, item.rack as rack, item.shelf as shelf, item.aisle as aisle, category.name as cName FROM invoice JOIN invoicedescription ON invoicedescription.invoiceID = invoice.invoiceID JOIN item ON item.itemID = invoicedescription.itemID JOIN category ON category.categoryID = item.categoryID WHERE invoice.invoiceID = {$invoiceID} ORDER BY formOrder, aisle, rack, shelf, iName";

		$invoiceData = queryDB($conn, $sql);

		if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
			echo "error: " . mysqli_error($conn);
			die("<br>Invoice is currently empty.");
		}
		closeDB($conn);
    ?>
<style>
th {
  padding:3px 5px 3px 5px;
}
.nameTags td {
    font-size: <?=$printFontSize?>vw;
}

</style>

<h3 class="hide_for_print">Client Order Form</h3>
<div class="body-content">
    <!-- Print button -->
    <button id="btn-print" onClick='AJAX_SetInvoicePrinted(<?=$invoiceID?>)'><i class='fa fa-print'></i>Print</button>
    <table class="report-container" border="0" cellspacing="0" cellpadding="0">
        <thead class="report-header">
            <tr><th>
            		<table class='table'>
                  <tr>
                    <th>Client</th>
                    <th>Time - Order Number</th>
                    <th>Family Size</th>
                    <th>Adults</th>
                    <th>Children</th>
                  </tr>
                  <tr>
                    <td><?=$name?></td>
                    <td><?=returnTime($visitTime)?> - <?=$LeanneNum?></td>
                    <td><?=familySizeDecoder($numOfKids + $numOfAdults)?></td>
                    <td><?=$numOfAdults?></td>
                    <td><?=$numOfKids?></td>
                  </tr>
                </table>
            </th></tr><tr></tr>
        </thead>
        <tbody><tr><td>
    <?php
		// Loop through our data and spit out the data into our table
		echo "<table style='padding:10px; font-size:1.4em;' id='orderTable'>
          <tr><th>Category</th><th>Item</th><th>Quantity</th><th>Aisle</th><th>Rack</th><th>Shelf</th></tr><tbody>";
		while( $invoice = sqlFetch($invoiceData) ) {
			echo "<tr>";
      echo "<td>" . $invoice['cName'] . "</td>";
			echo "<td>" . $invoice['iName'] . "</td>";
			echo "<td>" . ($invoice['iQty'] > 1 ? '*' : '') . $invoice['iQty'] . "</td>";
			echo "<td>" . aisleDecoder($invoice['aisle']) . "</td>";
			echo "<td>" . rackDecoder($invoice['rack']) . "</td>";
			echo "<td>" . shelfDecoder($invoice['shelf']) . "</td>";

			echo "</tr>";
		}
		echo "</tbody></table>";
	}
	else {
		echo "Something went wrong, please go back and try again.";
	} ?>
	
	</td></tr></tbody></table>
	
	<!-- name tags -->
	<div id="nameTags" style="display:none;padding-top:50px;">
	    <hr>
	    <table class="table nameTags" border=0>
        	<?php
        		$numLines = orderFormNameTagLength($numOfKids + $numOfAdults);
        		for ($i = 0; $i < $numLines; $i++) {
        			echo "<tr><td>" . $printName . "</td><td>" . $printName . "</td></tr>";
        		}
        	?>
        </table>
    </div>

	<div id="ErrorLog"></div>


<?php include 'php/footer.php'; ?>
<script src="js/orderFormOps.js"></script>