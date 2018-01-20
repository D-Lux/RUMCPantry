<?php


	include '../utilities.php';
	
	$availID = getAvailableClient();
	$deleted = isset($_GET['deleted']) ? $_GET['deleted'] : 0;
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
  
	// ***********************
  // * Build our column list
  // List all of the columns we want
  $columns = array("donationPartnerID", "name", "city", "phoneNumber");
	$searchableColumns = array("name", "city", "phoneNumber");
	
	// *********************************
    // * Generate our user search query 
    $searchVal = trim($_GET['search']['value']);
    $searchConditions = '';
    if ($searchVal != '') {
        $searchConditions = " AND (";
		
        $searchColumns = explode(",", implode(", OR ", $searchableColumns));

        foreach ($searchColumns as $column) {
            $searchConditions .= $column . " LIKE '%" . $searchVal . "%'";
        }
        $searchConditions .= ") ";
    }
	
	// *************************************
	// * ORDER clause
	$orderQuery = " ORDER BY name ";
	
	
	// *************************************
	// * Build the query 
	// Select our Query string
	$sql = "SELECT " . implode(", ", $columns);
	
	// FROM main table
	$sql .= " FROM DonationPartner ";
  
	// JOINs
	$sql .= " ";
				
	// WHERE clauses
	$sql .= " WHERE 1=1 ";
  
	// Get our total record count
	$totalRecordCount = count(returnAssocArray(queryDB($conn, $sql)));
	
	// Run our query with search and order conditions
  $results     = returnAssocArray(queryDB($conn, ($sql . $searchConditions . $orderQuery)));
	$recordCount = count($results);
	$returnData  = [];
	$out         = [];
	// Run our paging function using a for loop
	$showTo = ($_GET['length'] == -1) ? $recordCount : $_GET['length'];
    for ($i = $_GET['start']; $i < ($_GET['start'] + $showTo); $i++) {
        if ($i > ($recordCount - 1)) {
            break;
        }
        $row = [];
		
		//Build our link
		$viewLink = "/RUMCPantry/ap_do3.php?donationPartnerID=" . $results[$i]['donationPartnerID'];
		
		$viewLink = "<button type='submit' class='btn-table btn_edit' 
                    value='" . viewLink . "'><i class='fa fa-eye'> View</i></button>";
 
    $row[0] = $viewLink;
		$row[1] = $results[$i]['name'];
		$row[2] = $results[$i]['city'];
		$row[3] = displayPhoneNo($results[$i]['phoneNumber']);
		
		$out[] = $row;
	}	
	
	$returnData['draw']               = $_GET['draw'];
    $returnData['data']             = $out;
    $returnData['recordsTotal']     = $totalRecordCount;
    $returnData['recordsFiltered']  = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);
?>