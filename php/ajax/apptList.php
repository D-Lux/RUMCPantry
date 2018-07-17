<?php
  session_start();
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
include '../utilities.php';

// ***************************************************************************************--------------------------------------
// Set up server connection
$conn = connectDB();
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
		FROM invoice
		WHERE status NOT IN (" . implode(',', GetRedistributionStatuses()) . ") ";
		
// Get our total record count
$noSearchResults = runQuery($conn, ($sql . $tail));
if (!is_array($noSearchResults)) {
  $noResults = array("draw" => $_GET['draw'], "data" => 0, "recordsTotal" => 0, "recordsFiltered" => 0);
  die(json_encode($noResults));
}
$totalRecordCount = count($noSearchResults);

// Run our query with search and order conditions
$results = runQuery($conn, ($sql . $searchConditions . $tail));
closeDB($conn);


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
	
	// ************************************************
	
	$date = date("F jS, Y", strtotime($results[$i]["visitDate"])); // Month (full spelling) day (+suffix), YYYY
	$appLink = $basePath . "ap_ao3.php?date=" . $results[$i]['visitDate'];
	
	$row[0] = "<button type='submit' class='btn-edit btn-table' 
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

echo json_encode($returnData);

		
 ?>
