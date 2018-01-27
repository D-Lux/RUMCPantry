<?php

	include '../utilities.php';
	
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// *************************************
	// * Build the query 
	// Select our Query string
	$sql = " SELECT permission_id, login, permission_level, locked
            FROM permissions
            ORDER BY permission_level DESC ";
	
	
	// Run our query with search and order conditions
  $results = runQuery($conn, $sql);
	$recordCount = $totalRecordCount = count($results);
	$returnData = [];
	$out = [];
	// Run our paging function using a for loop
	foreach ($results as $result) {
        $row = [];
		
		//Build our links
		$editBtn = "<button class='btn-table btn-edit' value=" . $result['permission_id'] . ">Edit</button>";
    $delBtn = "";
    if ($result['locked'] == 0) {
      $delBtn .="<button type='submit' class='btn_dlt btn-icon' value=" . $result['permission_id'] . "><i class='fa fa-trash'></i></button>";
    }
   
		$row[0] = $result['login'];
		$row[1] = decodePermissionLevel($result['permission_level']);
    $row[2] = $editBtn;
		$row[3] = $delBtn;
		
		$out[] = $row;
	}

	
	
	$returnData['draw'] = $_GET['draw'];
  $returnData['data'] = $out;
  $returnData['recordsTotal'] = $totalRecordCount;
  $returnData['recordsFiltered'] = $recordCount;
	closeDB($conn);

	echo json_encode($returnData);//echo json_encode( $returnArr );
?>