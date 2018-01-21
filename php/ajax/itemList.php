<?php

  include '../utilities.php';
  $deleted = isset($_GET['deleted']) ? $_GET['deleted'] : 0;

  $conn = connectDB();
      /* Check connection*/
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  // *******************************************************************************
  // ***********************
  // * Build our column list
  // List all of the columns we want
  $columns = array("itemID", "itemName", "category.name as cName", "category.categoryID", "aisle", "rack", "shelf");
    
  $searchableColumns = array("itemName", "category.name", "aisle", "rack", "shelf");
    
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
  $orderQuery = " ORDER BY itemName ";


  // *************************************
  // * Build the query 
  // Select our Query string
  $sql = "SELECT " . implode(", ", $columns);
	
	// FROM main table
	$sql .= " FROM item ";

	// JOINs
	$sql .= " JOIN category
			  ON category.categoryID=item.categoryID ";
				
	// WHERE clauses
	// Rack set to -1 are strictly redistribution items
	$sql .= " 	WHERE item.isDeleted=" . $deleted . "
				AND (item.rack>=0
				OR item.rack IS NULL) ";
	
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
		
		
		$IDParam  = "itemID=" . $results[$i]['itemID'];
    
		$editLink = "/RUMCPantry/ap_io3.php?";
		$editLink   = "<button type='submit' class='btn-table btn-edit' " . (($deleted==1) ? "disabled" : " " ) . "
					   value='" . $editLink . $IDParam . "'><i class='fa fa-eye'> View</i></button>";
             
    $baseLink = "/RUMCPantry/php/itemOps.php?";		
		$actionLink = "<button type='submit' class='btn-icon" . (($deleted==1) ? " btn-reactivate" : "") . "'
					   value='" . $baseLink . 
					   (($deleted == 1) ? 'categoryID=' . $results[$i]['categoryID'] . '&ReactivateItem' : 'DeleteItem') . "=1&" . $IDParam . "'><i class='fa fa-" .
					   (($deleted == 1) ? "recycle" : "trash") . "'></i></button>";
		
		$row[0] = $editLink;
		$row[1] = $results[$i]['itemName'];
		$row[2] = $results[$i]['cName'];
		$row[3] = aisleDecoder($results[$i]['aisle']);
		$row[4] = rackDecoder($results[$i]['rack']);
		$row[5] = shelfDecoder($results[$i]['shelf']);
		$row[6] = $actionLink;
		
		$out[] = $row;
	}	
	
	$returnData['draw'] 			      = $_GET['draw'];
  $returnData['data']  		        = $out;
  $returnData['recordsTotal'] 	  = $totalRecordCount;
  $returnData['recordsFiltered']  = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);

 ?>
