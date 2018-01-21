 <?php

	include '../utilities.php';
  $deleted = isset($_GET['deleted']) ? $_GET['deleted'] : 0;

	$conn = connectDB();
		/* Check connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// *******************************************************************************
	// * Build our column list
  $columns = array("isDeleted", "categoryID", "name", "small", "medium", "large");
	
	$searchableColumns = array("name", "small", "medium", "large");;
	
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
	$sql .= " FROM Category ";

	// JOINs
	$sql .= " ";
				
	// WHERE clauses
	$sql .= " WHERE isDeleted = " . $deleted . "
			  AND name<>'Redistribution' ";
        
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
        
        $IDParam  = "categoryID=" . $results[$i]['categoryID'];
        $functionParam = ($deleted == 1) ? 'ReactivateCategory' : 'DeleteCategory';
        $classList = 'btn-icon' . (($deleted == 1) ? ' btn-reactivate' : ' ');
                 
        
        $editLink = "/RUMCPantry/ap_io5.php?";
        $editLink   = "<button type='submit' class='btn-table btn-edit' " . (($deleted==1) ? "disabled" : " " ) . "
                 value='" . $editLink . $IDParam . "'><i class='fa fa-eye'> View</i></button>";
        
        
        $baseLink = "/RUMCPantry/php/itemOps.php?";		
        $actionLink = "<button type='submit' class='" . $classList . "'
                 value='" . $baseLink . $functionParam . "=1&" . $IDParam . "'>
                 <i class='fa fa-" . (($deleted == 1) ? "recycle" : "trash") . "'></i></button>";
        
        $row[0] = $editLink;
        $row[1] = $results[$i]['name'];
        $row[2] = $results[$i]['small'];
        $row[3] = $results[$i]['medium'];
        $row[4] = $results[$i]['large'];
        $row[5] = $actionLink;
        
        $out[] = $row;
    }	
	
	$returnData['draw'] 			= $_GET['draw'];
    $returnData['data']  		    = $out;
    $returnData['recordsTotal'] 	= $totalRecordCount;
    $returnData['recordsFiltered']  = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);

 ?>
