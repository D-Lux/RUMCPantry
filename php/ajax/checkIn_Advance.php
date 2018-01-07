<?php

	include '../utilities.php';

	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		$dataBlock['error'] = "Connection failed: " . $conn->connect_error;
		die();
	}

  // field1 is the date - immediately usable by a sql query
  $date   = $_POST['field1'];
  // field2 is in the form of A###, where A denotes the status and the number following is the invoiceID
  $status = substr($_POST['field2'], 0, 1);
	$ID     = substr($_POST['field2'], 1);
  
  
  // Grab the invoice ID, then perform whichever action is necessary based on the invoice status
  // If invoice status is ACTIVE -> advance to order stage, update to Ordering Min + number of people above ACTIVE on same day
  // Might want to check for NOT redist.
  // If invoice status is in ORDER -> leave it be, this shouldn't ever happen
  // If invoice status is in Review -> open review screen with this invoice ID
  // If invoice status is in print -> open print screen with this invoice ID
  // If invoice status is in waiting -> Advance to completed
  // If invoice status is completed -> shouldn't ever happen
    
  switch ($status) {
    case 'D':
      advanceToOrdering($ID, $date);
      break;
    case 'O':
      break;
    case 'R':
      reviewOrder($ID);
      //break;
    case 'P':
      //printOrder($ID);
      break;
    case 'W':
      //advanceToCompleted($ID);
      break;
    case 'C':
      break;
  }
  
  
  function advanceToOrdering($ID, $date) {
    // Find all clients that are between the order and complete stages and set my status to Ordering Min + that number
    $sql = " SELECT COUNT(*) as ct
              FROM Invoice
              WHERE Invoice.visitDate = '" . $date . "'
              AND Invoice.status BETWEEN " . GetArrivedLow() . " AND " . GetCompletedStatus();
    $results = returnAssocArray(queryDB($conn, $sql));
    $currCount = current($results)['ct'];
    
    $sql = " UPDATE Invoice
             SET
  }
  
	
	//echo ("Oh, Hai Mawrk"); //json_encode($date)
?>