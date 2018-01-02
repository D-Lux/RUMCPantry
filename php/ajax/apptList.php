<?php

include '../utilities.php';

// ***************************************************************************************--------------------------------------
// Set up server connection
$conn = createPantryDatabaseConnection();
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$fromClient = (isset($_POST['cid']) ? TRUE : FALSE);
$cid = 0;
if ($fromClient) {
	$cid = $_POST['cid'];
}

// *********************************
// * Generate our user search query 
$searchVal = trim($_GET['search']['value']);
$searchConditions = '';
if ($searchVal != '') {
	$searchConditions = " AND (visitDate LIKE '%" . $searchVal . "%'
							OR MONTHNAME(visitDate) LIKE '%" . $searchVal . "%')";
}

// *********************************
// * Generate our tail query
$tail = " GROUP BY visitDate
		  ORDER BY visitDate DESC ";


// ***********************************
// * Build our base query
$sql = "SELECT  visitDate,
				SUM(CASE WHEN status = " . GetAvailableStatus() . " THEN 1 ELSE 0 END) as availCount,
				SUM(CASE WHEN status <> " . GetRedistributionStatus() . " THEN 1 ELSE 0 END) as numApp  
		FROM Invoice
		WHERE 1=1 ";
		
// Get our total record count
$totalRecordCount = count(returnAssocArray(queryDB($conn, ($sql . $tail))));

// Run our query with search and order conditions
$results = returnAssocArray(queryDB($conn, ($sql . $searchConditions . $tail)));
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
	
	// ************************************************
	
	$date = date("F jS, Y", strtotime($results[$i]["visitDate"])); // Month (full spelling) day (+suffix), YYYY
	$appLink = "/RUMCPantry/ap_ao3.php?date=" . $results[$i]['visitDate'];
	
	$row[0] = "<button type='submit' class='btn_edit' 
				   value='" . $appLink . "'><i class='fa fa-eye'> View</i></button>";
	$row[1] = $date;
	$row[2] = $results[$i]['numApp'];
	$row[3] = $results[$i]['availCount'];			
	$out[] = $row;
}	

$returnData['draw'] 			= $_GET['draw'];
$returnData['data']  		    = $out;
$returnData['recordsTotal'] 	= $totalRecordCount;
$returnData['recordsFiltered']  = $recordCount;
closeDB($conn);

echo json_encode($returnData);

		
 ?>