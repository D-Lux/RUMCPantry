<?php
include('../utilities.php');

$cid = isset($_POST['field1']) ? trim($_POST['field1']) : -1;

$response['status'] = -1;

if ($cid == -1) { die( json_encode($response)); }

$conn = connectDB();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the client selected already has an appointment today
// Future: Check whole month?
$sql = "SELECT invoiceID 
        FROM invoice
        WHERE clientID=" . $cid . "
        AND visitDate= CAST(NOW() AS DATE)";

$fetchedID = getSingleDataPoint($sql, $conn, "invoiceID");

closeDB($conn);

if (!$fetchedID) {
  $response['status'] = 1;
  die(json_encode($response));
}
else {
  $response['status'] = 2;
  die(json_encode($response));
}


?>