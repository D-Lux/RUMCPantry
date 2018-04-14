<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php

include '../utilities.php';

// ******************************************
// Set up server connection
$conn = connectDB();
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
// Get information to create link
$sql = "SELECT (numOfAdults + numOfKids) as familySize, lastName
		FROM client
		JOIN familymember
		ON client.clientID = familymember.clientID
		WHERE familymember.isHeadOfHousehold = 1
		AND client.clientID = " . $_GET['cid'];

$clientInfo = returnAssocArray(queryDB($conn, $sql ));
$familySize = $clientInfo[0]['familySize'];
$lastName = $clientInfo[0]['lastName'];

// Grab invoice information
$sql = "SELECT invoiceID, visitDate, status, visitTime
		FROM invoice
		WHERE clientID=" .  $_GET['cid'];
$results = returnAssocArray(queryDB($conn, $sql ));

closeDB($conn);

// Run our query with search and order conditions

if (!is_array($results)) {
  $noResults = array("draw" => $_GET['draw'], "data" => 0, "recordsTotal" => 0, "recordsFiltered" => 0);
  die(json_encode($noResults));
}

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
	$invoiceLink = $basePath . "ap_oo4e.php?invoiceID=" . $results[$i]['invoiceID'] . "&name=" . $lastName . 
					"&visitTime=" . $results[$i]['visitTime'] . "&familySize=" . $familySize ;
	
	$row[0] = "<button type='submit' class='btn-edit' 
				   value='" . $invoiceLink . "'><i class='fa fa-eye'> View</i></button>";
	$row[1] = date("F jS, Y", strtotime($results[$i]["visitDate"]));
	$row[2] = visitStatusDecoder($results[$i]['status']);			
	$out[]  = $row;
}	

$returnData['draw'] 			      = $_GET['draw'];
$returnData['data']  		        = $out;
$returnData['recordsTotal'] 	  = $recordCount;
$returnData['recordsFiltered']  = $recordCount;


echo json_encode($returnData);

		
 ?>
