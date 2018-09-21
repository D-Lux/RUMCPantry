<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
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
    $conn = connectDB();
    $invoiceID = ((isset($_GET['invoiceID'])) ? $_GET['invoiceID'] : 0);
    // Get Vars: invoiceID | name | visitTime | familySize

    $sql = "SELECT familymember.firstName, familymember.lastName, invoice.visitTime, invoice.status,
                  (numOfKids + numOfAdults) as familySize, client.notes
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
    $notes      = $results['notes'];
  
	// Create our query to get the invoice data
	if ( $name != null ) {

		//Connect to database
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

		//if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
		//	//echo "error: " . mysqli_error($conn);
		//	die("<br>Invoice is currently empty.");
		//}


    $sql = "SELECT visitDate FROM invoice where invoiceID = " . $invoiceID;
    $dateInfo = runQueryForOne($conn, $sql);

    closeDB($conn);
    ?>
    <button id="btn-print" onClick="window.print()">Print</button>
    <table class='tabcontent'>
      <thead>
        <tr>
          <th>
            <table class="table" style="padding:10px; font-size:1.4em;">
              <thead>
                <tr>
                  <th>Client</th>
                  <th>Appointment Date</th>
                  <th>Family Size</th>
                  <th>Invoice Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?=$name?></td>
                  <td><?=date("F dS, y", strtotime($dateInfo['visitDate']))?> - <?=returnTime($visitTime)?></td>
                  <td style="font-size:1.2em;"><?=$familySize?> (<?=familySizeDecoder($familySize)?>)</td>
                  <td>$<?=number_format($invtotal,2)?></td>
                </tr>
                <?php if (!empty($notes)) { ?>
                  <tr>
                    <td colspan=5>
                      <?=$notes?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <table id='orderTable' style="padding:10px; font-size:1.4em;">
              <thead>
                <tr>
                  <th>Category</th>
                  <th>Item</th>
                  <th>Quantity</th>
                  <th>Aisle</th>
                  <th>Rack</th>
                  <th>Shelf</th>
                  <th class="hide_for_print"></th>
                </tr>
              </thead>
              <!-- Loop through our data and spit out the data into our table -->
              <tbody>
                <?php if ($invoiceData == NULL || $invoiceData->num_rows <= 0){ ?>
                <tr>
                    <td colspan = 7>Invoice is currently empty.</td>
                </tr>
                <?php
		        }
		        else {
		            while( $invoice = sqlFetch($invoiceData) ) { ?>
                  <tr>
                    <td><?=$invoice['cName']?></td>
                    <td><?=$invoice['iName']?></td>
                    <td><?=$invoice['iQty']?></td>
                    <td><?=aisleDecoder($invoice['aisle'])?></td>
                    <td><?=rackDecoder($invoice['rack'])?></td>
                    <td><?=shelfDecoder($invoice['shelf'])?></td>
                    <td class='hide_for_print'>
                      <button type='submit' class='btn-icon' name='RemoveItem' onclick='AJAX_RemoveFromInvoice(this)'>
                      <i class='fa fa-trash'></i></button>
                    </td>
                  </tr>
                <?php } } ?>
              </tbody>
    		    </table>
          </td>
        </tr>
    	</tbody>
    </table>	
	<br>
    <?php
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
?>
 
<div id='ErrorLog'></div>

<?php include 'php/footer.php'; ?>
<script src="js/orderFormOps.js"></script>