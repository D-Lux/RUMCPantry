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
	// Query the database for all active orders
	// Create list of all orders, selectable to view that order
	
	$sql = "SELECT FMC_Join.lName as ln, invoiceID, visitTime, status
			FROM Invoice
			JOIN (SELECT FamilyMember.lastName as lName, FamilyMember.clientID as JoinID
				  FROM FamilyMember
				  JOIN Client
				  WHERE Client.clientID=FamilyMember.clientID
				  AND isHeadOfHousehold=TRUE) as FMC_Join
			WHERE FMC_Join.JoinID=Invoice.clientID
			AND status>=" . GetActiveStatus() . "
			AND status<=" . HighestActiveStatus();
	
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$invoiceData = queryDB($conn, $sql);
	
	if ($invoiceData == NULL || $invoiceData->num_rows <= 0){
		echo "No appointments are currently active.";	
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