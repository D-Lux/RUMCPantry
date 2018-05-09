<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
  session_start();
include '../utilities.php';

$pID = $_GET['pid'];
// ******************************************
// Set up server connection
$conn = connectDB();

// Get our data
$sql = "SELECT donationID, dateOfPickup
        FROM donation WHERE donationPartnerID = " . $pID;
        
$results = returnAssocArray(queryDB($conn, $sql ));

closeDB($conn);

// Run our query with search and order conditions
if (!is_array($results)) {
  $returnData['draw'] 			      = $_GET['draw'];
  $returnData['data']  		        = $results;
  $returnData['recordsTotal'] 	  = 0;
  $returnData['recordsFiltered']  = 0;
  die( json_encode($returnData));
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
	// Build our link
	$donationLink = $basePath . "ap_do4.php?donationID=" . $results[$i]['donationID'];
	
	$row[0] = "<button type='submit' class='btn-edit' 
				   value='" . $donationLink . "'><i class='fa fa-eye'> View</i></button>";
	$row[1] = date("F jS, Y", strtotime($results[$i]["dateOfPickup"]));
		
	$out[]  = $row;
}	

$returnData['draw'] 			= $_GET['draw'];
$returnData['data']  		    = $out;
$returnData['recordsTotal'] 	= $recordCount;
$returnData['recordsFiltered']  = $recordCount;


echo json_encode($returnData);

		
 ?>
