 <?php

	include '../utilities.php';

	$conn = connectDB();
		/* Check connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


  $sql = "SELECT MAX(formOrder) as maxOrder FROM category WHERE isDeleted=0";
  $maxO = current(runQuery($conn, $sql))['maxOrder'];

  $limit = '';
  if ($_GET['length'] != -1) {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
  }

	// *******************************************************************************
	// * Build our column list
  $sql = "SELECT category.categoryID, category.name, formOrder, COUNT(*) as itemQty
          FROM category
          JOIN item
            ON category.categoryid=item.categoryid
          WHERE category.isDeleted = 0
          AND item.isDeleted=0
          AND category.name != 'Redistribution'
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
    $upArrow = $result['formOrder'] != 1
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
