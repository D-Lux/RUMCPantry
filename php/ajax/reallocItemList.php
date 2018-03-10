<?php
	include '../utilities.php';

  $deleted = isset($_GET['deleted']) ? 1 : 0;
  // Set up server connection
  $conn = connectDB();
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // ***********************
  // * Build our column list
  // List all of the columns we want
  $columns = array("itemID", "itemName", "price", "item.aisle as weight");
  $searchableColumns = array("itemName", "price", "item.aisle");

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
  $orderQuery = " ORDER BY " . $columns[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " ";


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
  $sql .= " FROM item ";

  // JOINs
  $sql .= " JOIN category
            ON item.categoryID=category.categoryID ";

  // WHERE and ORDER clauses
  $sql .= " WHERE category.name='REDISTRIBUTION'
            AND item.isDeleted=" . $deleted;

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
      //Build our links
      $IDParam  = "&id=" . $result['itemID'];

      $editLink = "<button type='submit' class='btn-table btn-edit'
               value='/RUMCPantry/php/redistOps.php?updateRedistItem=1" . $IDParam . "'><i class='fa fa-eye'> View</i></button>";

      $actionLink = "<button type='submit' class='btn-icon" . (($deleted==1) ? " btn-reactivate" : "") . "'
               value='/RUMCPantry/php/redistOps.php?" .
               (($deleted == 1) ? 'activateRedistItem' : 'deleteRedistItem') . "=1" . $IDParam . "'><i class='fa fa-" .
               (($deleted == 1) ? "recycle" : "trash") . "'></i></button>";

      $row[0] = $editLink;
      $row[1] = $result['itemName'];
      $row[2] = $result['price'];
      $row[3] = $result['weight'];
      $row[4] = $actionLink;

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