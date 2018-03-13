<?php
$pageRestriction = 99;
include 'php/header.php';
include 'php/backButton.php';

$conn = connectDB();

// Create our queries
$sql = "SELECT itemName, quantity, totalItemsPrice
				FROM invoicedescription
				JOIN item
				ON item.itemID=invoicedescription.itemId
				WHERE invoicedescription.invoiceID=" . fixInput($_GET['id']);
$items = runQuery($conn, $sql);

$sql = "SELECT lastName as Name, visitDate
				FROM invoice
				JOIN client
				ON client.clientID = invoice.clientID
				JOIN familymember
				ON familymember.clientID = invoice.clientID
				WHERE invoice.invoiceID=" . $_GET['id'];
$partner = runQueryForOne($conn, $sql);

if ($items === false) {
	die("No items in this invoice");
}
if ($partner === false) {
	die("Invalid partner");
}

closeDB($conn);
?>
  <h3>View Reallocation</h3>

	<div class="body-content">
		<div class="row">
			<div class="col-sm-2 text-right">Partner:</div>
			<div class="col-sm-6"><?=$partner['Name']?></div>
		</div>
		<div class="row">
			<div class="col-sm-2 text-right">Date:</div>
			<div class="col-sm-6"><?=date('F jS, Y', strtotime($partner['visitDate']))?></div>
		</div>
		<div class="clearfix"></div>
		<table class="table table-hover">
			<thead class="thead-dark">
				<tr>
					<th>Item</th>
					<th>Quantity</th>
					<th>Price of Items</th>
				</tr>
			</thead>
			<?php foreach($items as $item) { ?>
				<tr>
					<td><?=$item['itemName']?></td>
					<td><?=$item['quantity']?></td>
					<td><?=$item['totalItemsPrice']?></td>
				</tr>
			<?php } ?>
		</table>

<?php include 'php/footer.php'; ?>