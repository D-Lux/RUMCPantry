 <?php
  session_start();
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
	include '../utilities.php';


	$conn = connectDB();
		/* Check connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


  $ord = substr($_GET['ord'], 0, strpos($_GET['ord'], "_"));
  $cid = substr($_GET['ord'], strpos($_GET['ord'], "_") + 1);


  // find ord + or - 1, move to this ord, set this ord to this -1 or +1

  $dir = isset($_GET['up']) ? -1 : 1;

  // Move the item in the space we're going to
  $sql = "UPDATE category SET formOrder = " . $ord . " WHERE formOrder = " . ($ord + $dir);
  queryDB($conn, $sql);

  $sql = "UPDATE category SET formOrder = " . ($ord + $dir) . " WHERE categoryID = " . $cid;
  queryDB($conn, $sql);

 ?>
