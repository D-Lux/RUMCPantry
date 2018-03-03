<?php
	include '../utilities.php';


  $checkID = isset($_GET['id']);

  $idWhere = ($checkID) ? " AND clientID=" . fixInput(trim($_GET['id'])) : "";

	// Set up server connection
	$conn = connectDB();

	// ***********************
  // * Build our column list
  // List all of the columns we want
  $columns = array("I.invoiceID", "I.visitDate", "fm.lastName");
	$searchableColumns = array("I.visitDate", "fm.lastName", "MONTH_NAME(I.visitDate)", "YEAR(I.visitDate)");

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
	$orderQuery = " ORDER BY I.visitDate ";

  // *************************************
  // * LIMIT clause
  $limitQuery = "";
  if ($_GET['length'] > -1) {
    $limitQuery = " LIMIT {$_GET['start']}, {$_GET['length']} ";
  }

	// *************************************
	// * Build the query
	// Select our Query string
	$sql = "SELECT " . implode(", ", $columns);

	// FROM main table
	$sql .= " FROM Invoice I ";

	// JOINs
	$sql .= " JOIN Client c
            ON c.clientID=fm.clientID
            JOIN FamilyMember fm
            ON fm.clientID=I.clientID ";

	// WHERE and ORDER clauses
	$sql .= " WHERE c.redistribution=1 " . $idWhere;

	// Get our total record count
  $totalResults = runQuery($conn, $sql);
  $totalRecordCount = 0;
  if (is_array($totalResults)) {
    $totalRecordCount = count(runQuery($conn, $sql));
  }

	// Run our query with search, order, and limit conditions
  $results = runQuery($conn, ($sql . $searchConditions . $orderQuery . $limitQuery));

  $recordCount = 0;
  if (is_array($results)) {
    $recordCount = count($results);
  }
	$returnData = [];
	$out = [];

  // Generate table data
  if ($recordCount > 0) {
    foreach ($results as $result) {
      //Build our link
      $date = DATE("F d, Y", strtotime($result['visitDate']));
      $editLink   = "<button type='submit' class='btn-table btn-edit'
               value='/RUMCPantry/ap_ro10.php?id=" . $result['invoiceID'] . "'><i class='fa fa-eye'> " . $date . "</i></button>";

      $col = 0;
      $row[0] = $editLink;
      // Only show name if we're looking at everyone
      if (!$checkID) {
        $row[1] = $result['lastName'];
      }
      $out[] = $row;
    }
  }


	$returnData['draw'] = $_GET['draw'];
  $returnData['data'] = $out;
  $returnData['recordsTotal'] = $totalRecordCount;
  $returnData['recordsFiltered'] = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);//echo json_encode( $returnArr );
?>