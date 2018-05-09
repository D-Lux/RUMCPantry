<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
  session_start();
	include '../utilities.php';

	// Set up server connection
	$conn = connectDB();

  $deleted = isset($_GET['deleted']) ? 1 : 0;

	// ***********************
  // * Build our column list
  // List all of the columns we want
  $columns = array("fm.lastName, c.clientID, c.email, c.phoneNumber");
	$searchableColumns = array("fm.lastName, c.email, c.phoneNumber");

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
	$sql .= " FROM client c ";

	// JOINs
	$sql .= " JOIN familymember fm
            ON c.clientID=fm.clientID ";

	// WHERE and ORDER clauses
	$sql .= " WHERE c.redistribution=1
            AND c.isDeleted = {$deleted} ";

	// Get our total record count
  $totalResults = runQuery($conn, $sql);
  $totalRecordCount = 0;
  if (is_array($totalResults)) {
    $totalRecordCount = count(runQuery($conn, $sql));
  }

	// Run our query with search, order, and limit conditions
  $results = runQuery($conn, ($sql . $searchConditions . $orderQuery . $limitQuery));

  closeDB($conn);

  $recordCount = 0;
  if (is_array($results)) {
    $recordCount = count($results);
  }
	$returnData = [];
	$out = [];

  // Generate table data
  if ($recordCount > 0) {
    foreach ($results as $result) {
      $col = 0;

      if ($deleted == 0) {
        $editLink   = "<button type='submit' class='btn-table btn-edit'
               value='" . $basePath . "ap_ro4.php?id=" . $result['clientID'] . "'><i class='fa fa-eye'> View</i></button>";
        $row[$col++] = $editLink;
      }
      $row[$col++] = $result['lastName'];
      $row[$col++] = $result['email'];
      $row[$col++] = displayPhoneNo($result['phoneNumber']);


      $baseLink = $basePath . "php/redistOps.php?";
      $IDParam  = "&id=" . $result['clientID'];
      $actionLink = "<button type='submit' class='btn-icon" . (($deleted==1) ? " btn-reactivate" : "") . "'
             value='" . $baseLink .
             (($deleted == 1) ? 'activateRedist' : 'deleteRedist') . "=1" . $IDParam . "'><i class='fa fa-" .
             (($deleted == 1) ? "recycle" : "trash") . "'></i></button>";

      $row[$col++] = $actionLink;

      $out[] = $row;
    }
  }


	$returnData['draw'] = $_GET['draw'];
  $returnData['data'] = $out;
  $returnData['recordsTotal'] = $totalRecordCount;
  $returnData['recordsFiltered'] = $recordCount;

	echo json_encode($returnData);
?>