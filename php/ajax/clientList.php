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
  $columns = array("Client.clientID", "(Client.numOfAdults + Client.numOfKids) as familySize", 
				   "Client.email", "Client.phoneNumber", "CONCAT(fm.lastName, ', ', fm.firstName) as cName");
	$searchableColumns = array("Client.email", "Client.phoneNumber", "fm.lastName" , "fm.firstName");
	
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
	$orderQuery = " ORDER BY fm.lastName ";
	
	
	// *************************************
	// * Build the query 
	// Select our Query string
	$sql = "SELECT " . implode(", ", $columns);
	
	// FROM main table
	$sql .= " FROM FamilyMember fm ";
	
	// JOINs
	$sql .= " JOIN Client 
				ON Client.clientID=fm.clientID ";
				
	// WHERE clauses
	$sql .= " 	WHERE Client.isDeleted=" . $deleted . "
				AND	Client.clientID<>" . $availID . "
				AND	fm.isHeadOfHousehold=true
				AND	Client.redistribution=0 ";
	
	// Get our total record count
	$totalRecordCount = count(returnAssocArray(queryDB($conn, $sql)));
	
	// Run our query with search and order conditions
    $results = returnAssocArray(queryDB($conn, ($sql . $searchConditions . $orderQuery)));
	$recordCount = count($results);
	$returnData = [];
	$out = [];
	// Run our paging function using a for loop
	$showTo = ($_GET['length'] == -1) ? $recordCount : $_GET['length'];
    for ($i = $_GET['start']; $i < ($_GET['start'] + $showTo); $i++) {
        if ($i > ($recordCount - 1)) {
            break;
        }
        $row = [];
		
		//Build our links
		$baseLink = "/RUMCPantry/php/clientOps.php?";
		$IDParam  = "&id=" . $results[$i]['clientID'];
		
		$editLink   = "<button type='submit' class='btn-table btn_edit' 
					   value='" . $baseLink . "GoUpdateClient=1" . $IDParam . "'><i class='fa fa-eye'> View</i></button>";
             
		$actionLink = "<button type='submit' class='btn_icon" . (($deleted==1) ? " btn_reactivate" : "") . "'
					   value='" . $baseLink . 
					   (($deleted == 1) ? 'ActiveClient' : 'InactiveClient') . "=1" . $IDParam . "'><i class='fa fa-" .
					   (($deleted == 1) ? "recycle" : "trash") . "'></i></button>";
		
		$col = 0;
		// Don't allow editing if we're deleted (For now, complicates the view client page)
		if ($deleted == 0) {
			$row[$col++] = $editLink;
		}
		$row[$col++] = $results[$i]['cName'];
		$row[$col++] = $results[$i]['familySize'];
		$row[$col++] = displayForTable($results[$i]['email'], 20);
		$row[$col++] = displayPhoneNo($results[$i]['phoneNumber']);
		$row[$col++] = $actionLink;
		
		$out[] = $row;
	}

	
	
	$returnData['draw'] = $_GET['draw'];
    $returnData['data'] = $out;
    $returnData['recordsTotal'] = $totalRecordCount;
    $returnData['recordsFiltered'] = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);//echo json_encode( $returnArr );
?>