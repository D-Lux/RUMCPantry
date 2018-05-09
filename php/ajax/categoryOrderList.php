<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  session_start();
	include '../utilities.php';

	$conn = connectDB();
		/* Check connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


  $sql = "SELECT MAX(formOrder) as maxOrder, MIN(formOrder) as minOrder FROM category WHERE isDeleted=0";
  $range = runQueryForOne($conn, $sql);
  $maxO = $range['maxOrder'];
  $minO = $range['minOrder'];


  $limit = '';
  if ($_GET['length'] != -1) {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
  }

	// *******************************************************************************
	// * Build our column list
  $sql = "SELECT category.categoryID, category.name, formOrder, COUNT(item.itemID) as itemQty
          FROM category
          LEFT JOIN item
            ON category.categoryid=item.categoryid
            AND item.isDeleted = 0
          WHERE category.isDeleted = 0
          AND category.name != 'Redistribution'
          AND category.formOrder > 0
          GROUP BY category.categoryID
          ORDER BY formOrder ASC";

  // Run our query
  $results = runQuery($conn, $sql);
  $recordCount = 0;
  if (is_array($results)) {
    $recordCount = count($results);
  }

  $results = runQuery($conn, $sql . $limit);

	$returnData = [];
	$out = [];

  foreach($results as $result) {
    $row = [];

    $ord = $result['formOrder'];
    $id = $result['categoryID'];
    $upArrow = $result['formOrder'] > $minO
                ? "<a href='#' class='btn-up' id='up" . $ord . "_" . $id . "'><i class='fa fa-arrow-up'></i></a>"
                : '';

    $downArrow = $result['formOrder'] < $maxO
                ?  "<a href='#' class='btn-down' id='dn" . $ord . "_" . $id . "'><i class='fa fa-arrow-down'></i></a>"
                : '';

    $row[0] = $result['formOrder'];
    $row[1] = $result['name'];
    $row[2] = $result['itemQty'];
    $row[3] = $upArrow . $downArrow;

    $out[] = $row;

  }

	$returnData['draw'] 			      = $_GET['draw'];
  $returnData['data']  		        = $out;
  $returnData['recordsTotal'] 	  = $recordCount;
  $returnData['recordsFiltered']  = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);

 ?>
