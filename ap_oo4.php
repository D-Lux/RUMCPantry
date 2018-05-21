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
                  numOfKids, numOfAdults, client.notes
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
  $notes      = $results['notes'];
    
  $printFontSize = round(100 / (strlen($printName) * 2),2,PHP_ROUND_HALF_UP);


	if ( ($name != null) && ($invoiceID != 0) ){
		// Create our query to get the invoice data
    $sql = "SELECT item.itemname as iName, invoicedescription.quantity as iQty, item.rack as rack, item.shelf as shelf, item.aisle as aisle, category.name as cName 
            FROM invoice
            JOIN invoicedescription
              ON invoicedescription.invoiceID = invoice.invoiceID
            JOIN item 
              ON item.itemID = invoicedescription.itemID
            JOIN category 
              ON category.categoryID = item.categoryID 
            WHERE invoice.invoiceID = {$invoiceID}
            ORDER BY aisle, rack, shelf, formOrder, iName";

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
    <table class='tabcontent'>
      <thead>
        <tr>
          <th>
            <table class='table' style='padding:10px; font-size:1.4em;'>
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
                <td style="font-size:1.2em;"><?=($numOfKids + $numOfAdults)?> (<?=familySizeDecoder($numOfKids + $numOfAdults)?>)</td>
                <td><?=$numOfAdults?></td>
                <td><?=$numOfKids?></td>
              </tr>
              <?php if (!empty($notes)) { ?>
                <tr>
                  <td colspan=5>
                    <?=$notes?>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </th>
        </tr>
        <tr></tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <!-- Loop through our data and spit out the data into our table -->
            <table style='padding:10px; font-size:1.4em;' id='orderTable'>
              <thead>
                <tr>
                  <th>Category</th>
                  <th>Item</th>
                  <th>Quantity</th>
                  <th>Aisle</th>
                  <th>Rack</th>
                  <th>Shelf</th>
                </tr>
              </thead>
              <tbody>
                <?php while( $invoice = sqlFetch($invoiceData) ) { ?>
                  <tr>
                    <td><?=$invoice['cName']?></td>
                    <td><?=$invoice['iName']?></td>
                    <td><?=($invoice['iQty'] > 1 ? '*' : '') . $invoice['iQty']?></td>
                    <td><?=aisleDecoder($invoice['aisle'])?></td>
                    <td><?=rackDecoder($invoice['rack'])?></td>
                    <td><?=shelfDecoder($invoice['shelf'])?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
	<?php }
	else {
		echo "Something went wrong, please go back and try again.";
	} ?>
	

	
	<!-- name tags -->
	<div id="nameTags" style="display:none;padding-top:50px;">
	    <hr>
	    <table class="table nameTags">
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