<?php
session_start();
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
include('utilities.php');

if (isset($_POST['createCategory'])) {
    $name 	= fixInput($_POST['category']);
    $small 	= $_POST['small']; 
    $medium = $_POST['medium'];
    $large 	= $_POST['large'];

    /* Create connection*/
    $conn = connectDB();
    /* Check connection*/
    if ($conn->connect_error) {
      createCookie("errConnection", 1, 30);
      redirectPage("ap_io8.php");
    } 
  
    // Check if there is a category with the same name already
    $sql = "SELECT isDeleted FROM category WHERE name='" . $name . "'";
    
    $results = runQuery($conn, $sql);
    if (is_array($results) && count($results) > 0) {
      if (current($results)['isDeleted']) {
        $sql = "UPDATE category SET isDeleted = false WHERE name = '" . $name . "'";
        createCookie("categoryReactivated", 1, 30);
      }
      else {
        createCookie("CatExists", 1, 30);
      }
    }
    
    else {
      $sql = "SELECT Max(formOrder) as M FROM category";
      $nextOrder = runQueryForOne($conn, $sql)['M'] + 1;
      $sql = "INSERT INTO category (name, small, medium, large, isDeleted, formOrder)
              VALUES ('" . $name . "', " . $small . ", " . $medium . ", " . $large . ", 0, " . $nextOrder . ")";

      if (queryDB($conn, $sql) === TRUE) {
        createCookie("newCategory", 1, 30);      
      } 
      else {
        createCookie("errCreate", 1, 30);      
      }
    }
    closeDB($conn);
    redirectPage("ap_io8.php");
}
elseif (isset($_POST['UpdateCategoryIndividual'])) {
    $categoryID = $_POST['categoryID'];
    $name 	 	  = fixInput($_POST['category']);
    $small 		  = $_POST['small']; 
    $medium 	  = $_POST['medium'];
    $large 		  = $_POST['large'];

    /* Create connection*/
    $conn = connectDB();
    /* Check connection*/
    if ($conn->connect_error) {
      createCookie("errConnection", 1, 30);
      redirectPage("ap_io8.php"); 
    } 

    //check to see if category exists, if not create it.
    $sql = "UPDATE category
            SET categoryID = "  . $categoryID . ", 
                name 	     = '" . $name       . "', 
                small      = "  . $small      . ", 
                medium     = "  . $medium     . ", 
                large      = "  . $large      . " 
            WHERE categoryID = $categoryID";


    if (queryDB($conn, $sql) === TRUE) {
      createCookie("categoryUpdated", 1, 30);
      closeDB($conn);
      redirectPage("ap_io8.php"); 
    } 
    else {
      createCookie("errUpdate", 1, 30);
      redirectPage("ap_io8.php");   
    }
  closeDB($conn);
}
elseif (isset($_GET['DeleteCategory'])) {
  $conn = connectDB();
  if ($conn->connect_error) {
    createCookie("conErr", 1, 30);
    redirectPage("ap_io8.php");
  } 
  $categoryID = $_GET['categoryID']; 
  $sql = "UPDATE category SET isDeleted = true WHERE categoryID=" . $categoryID;
    
	if (queryDB($conn, $sql) === FALSE) {
    createCookie("delError", 1, 30);
  }
  else {
    $sql = "UPDATE item SET isDeleted = true WHERE categoryID=$categoryID";
    if (queryDB($conn, $sql) === FALSE) {
      createCookie("delError", 1, 30);
    }
    else{
      createCookie("categoryDeleted", 1, 30);
    }
  }
  closeDB($conn);
  redirectPage("ap_io8.php");
}
elseif (isset($_GET['ReactivateCategory'])) {
  $conn = connectDB();
  if ($conn->connect_error) {
    createCookie("conErr", 1, 30);
  }

	$sql = "UPDATE category SET isDeleted = false WHERE categoryID=" . $_GET['categoryID'];

  if (queryDB($conn, $sql) === FALSE) {
    createCookie("reactivateError", 1, 30);
  }
  else{
    createCookie("categoryReactivated", 1, 30);
  }
  closeDB($conn);
  redirectPage("ap_io8.php?showDeleted=1");
}

?>