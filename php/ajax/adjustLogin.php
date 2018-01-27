<?php

  include '../utilities.php';

  $name       = isset($_GET['n'])    ? fixInput($_GET['n']) : '';
  $password   = isset($_GET['pw'])   ? $_GET['pw']          : '';
  $permission = isset($_GET['perm']) ? $_GET['perm']        : '';
  $pID        = isset($_GET['id'])   ? $_GET['id']          : '';
  $ePW        = password_hash($password, PASSWORD_BCRYPT, ['cost' => 8]);

  
  // Set up server connection
  $conn = connectDB();
  $returnArr = [];
	if ($conn->connect_error) {
    $returnArr['msg'] = "Database connection error, Login creation failed.";
    $returnArr['err'] = 1;
    die(json_encode($returnArr));
	} 
  
  if (isset($_GET['create'])) {
    // Create insertion string
    $sql = "INSERT INTO Permissions 
          (login, permission_level, pw, locked)
          VALUES 
          ('" . $name . "', " . $permission . ", '" . $ePW . "', 0)";

    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      $returnArr['msg'] = "New login created!";
      $returnArr['err'] = 0;
    }
    else {
      $returnArr['msg'] = "Insertion error, Login creation failed.";
      $returnArr['err'] = 1;
    }
     
    die(json_encode($returnArr));
  }
  else if (isset($_GET['dellogin'])) {
    // Create insertion string
    $sql = "DELETE FROM Permissions 
            WHERE permission_id = " . $pID;
    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      $returnArr['msg'] = "Login removed!";
      $returnArr['err'] = 0;
    }
    else {
      $returnArr['msg'] = "Deletion error.";
      $returnArr['err'] = 1;
    }
     
    die(json_encode($returnArr));
  }
  
  else if (isset($_GET['showEdit'])) {
    // Create insertion string
    $sql = "SELECT login, permission_level, locked
            FROM permissions
            WHERE permission_id = " . $pID;
    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      $returnArr['msg'] = "Login removed!";
      $returnArr['err'] = 0;
    }
    else {
      $returnArr['msg'] = "Error getting record, please refresh the page.";
      $returnArr['err'] = 1;
    }
     
    die(json_encode($returnArr));
  }

	

?>