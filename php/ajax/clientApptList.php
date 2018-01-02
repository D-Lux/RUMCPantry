<?php

include '../utilities.php';

// ******************************************
// Set up server connection
$conn = createPantryDatabaseConnection();
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
// Get information to create link
$sql = "SELECT (numOfAdults + numOfKids) as familySize, lastName
		FROM Client
		JOIN FamilyMember
		ON Client.clientID = FamilyMember.clientID
		WHERE FamilyMember.isHeadOfHousehold = 1
		AND Client.clientID = " . $_GET['cid'];

$clientInfo = returnAssocArray(queryDB($conn, $sql ));
$familySize = $clientInfo[0]['familySize'];
$lastName = $clientInfo[0]['lastName'];

// Grab invoice information
$sql = "SELECT invoiceID, visitDate, status, visitTime
		FROM Invoice
		WHERE clientID=" .  $_GET['cid'];
$results = returnAssocArray(queryDB($conn, $sql ));

closeDB($conn);

// Run our query with search and order conditions
$recordCount = count($results);
$returnData = [];
$out = [];

// ************************************************************************************
// Run our paging function using a for loop
$showTo = ($_GET['length'] == -1) ? $recordCount : $_GET['length'];
for ($i = $_GET['start']; $i < ($_GET['start'] + $showTo); $i++) {
	if ($i > ($recordCount - 1)) {
		break;
	}
	$row = [];
	// Build our link to view the invoice
	$invoiceLink = "/RUMCPantry/ap_oo4e.php?invoiceID=" . $results[$i]['invoiceID'] . "&name=" . $lastName . 
					"&visitTime=" . $results[$i]['visitTime'] . "&familySize=" . $familySize ;
	
	$row[0] = "<button type='submit' class='btn_edit' 
				   value='" . $invoiceLink . "'><i class='fa fa-eye'> View</i></button>";
	$row[1] = date("F jS, Y", strtotime($results[$i]["visitDate"]));
	$row[2] = visitStatusDecoder($results[$i]['status']);			
	$out[]  = $row;
}	

$returnData['draw'] 			= $_GET['draw'];
$returnData['data']  		    = $out;
$returnData['recordsTotal'] 	= $recordCount;
$returnData['recordsFiltered']  = $recordCount;


echo json_encode($returnData);

		
 ?>
