<?php

	include '../utilities.php';

	$conn = connectDB();
  
  $invoiceID = $_GET['id'];
  $newStatus = $_GET['status'];
  
  
  $sql = "UPDATE Invoice SET status = {$newStatus} WHERE invoiceID = {$invoiceID}";

  queryDB($conn, $sql);

?>