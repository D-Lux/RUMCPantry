<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
	include '../utilities.php';

	$conn = connectDB();

  $invoiceID = $_GET['id'];
  $newStatus = $_GET['status'];


  $sql = "UPDATE invoice SET status = {$newStatus} WHERE invoiceID = {$invoiceID}";

  queryDB($conn, $sql);

?>