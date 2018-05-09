<?php
session_start();
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
include('utilities.php');

if(isset($_POST['createItem'])) {
  $category 	  = fixInput($_POST['category']);
  $itemName 	  = fixInput($_POST['itemName']);
  $displayName  = fixInput($_POST['displayName']);
  $price 		    = $_POST['price'];
  $small 		    = $_POST['small'];
  $medium 	    = $_POST['medium'];
  $large 		    = $_POST['large'];
  $aisle 		    = aisleEncoder($_POST['aisle']);
  $rack 		    = rackEncoder($_POST['rack']);
  $shelf 		    = shelfEncoder($_POST['shelf']);
  $categoryID   = null;
	$newCategory  = FALSE;


  // Connect to the DB and test
  $conn = connectDB();
  if ($conn->connect_error) {
    createCookie("errConnection", 1, 30);
    redirectPage("ap_io7.php");
  }

    //check to see if category exists, if not create it.
	$result = queryDB($conn, "SELECT DISTINCT categoryID FROM category WHERE name = '" . $category . "'");
	if($result->num_rows == 0) {
    $sql = "SELECT Max(formOrder) as M FROM category";
    $nextOrder = runQueryForOne($conn, $sql)['M'] + 1;
		$sql = "INSERT INTO category (name, small, medium, large, isDeleted, formOrder)
            VALUES ('" . $category . "', " . $small . ", " . $medium . ", " . $large . ", 0, " . $nextOrder . ")";
		if (queryDB($conn, $sql) === TRUE) {
			$newCategory = TRUE;
			$categoryID = $conn->insert_id;
    }
		else {
			closeDB($conn);
			createCookie("errCreate", 1, 30);
			redirectPage("ap_io7.php");
		}
  }
	else {
		$row = sqlFetch($result);
    $categoryID = $row["categoryID"];
  }


    $sql = "INSERT INTO item (itemName, displayName, price, isDeleted, small, medium, large, categoryID, aisle, rack, shelf)
    VALUES ('$itemName', '$displayName', '$price', 0, '$small', '$medium', '$large', '$categoryID', '$aisle', '$rack', '$shelf')";

  if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		createCookie("newItem", 1, 30);
		redirectPage("ap_io7.php");
  }
	else {
		if ($newCategory) {
			queryDB($conn, "DELETE FROM category WHERE categoryID = " . $categoryID);
		}
		closeDB($conn);
    createCookie("errCreate", 1, 30);
		redirectPage("ap_io7.php");
  }
}
elseif (isset($_GET['DeleteItem'])) {
	$itemID = $_GET['itemID'];
    $conn = connectDB();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = queryDB($conn, "SELECT DISTINCT itemID FROM item WHERE itemID = " . $itemID);
    if($result->num_rows > 0) {

      $sql = "update item set isDeleted=true where itemID=" . $itemID;

      if (queryDB($conn, $sql) === TRUE) {
        createCookie("itemDeleted", 1, 30);

      }
      else{
        createCookie("errConnection", 1, 30);
      }
      closeDB($conn);
      redirectPage("ap_io7.php");
    }

}
elseif (isset($_POST['updateItemIndividual'])) {
  $itemID   	 = $_POST['itemID'];
  $category 	 = fixInput($_POST['category']);
  $itemName 	 = fixInput($_POST['itemName']);
  $displayName = fixInput($_POST['displayName']);
  $price 		   = $_POST['price'];
  $small 		   = $_POST['small'];
  $medium 	   = $_POST['medium'];
  $large 		   = $_POST['large'];
  $aisle 		   = $_POST['aisle'];
  $rack 		   = $_POST['rack'];
  $shelf 		   = $_POST['shelf'];

  $categoryID = null;

  /* Create connection*/
  $conn = connectDB();
  /* Check connection*/
  if ($conn->connect_error) {
    createCookie("errConnection", 1, 30);
    redirectPage("ap_io7.php");
  }

  //check to see if category exists, if not create it.
  $result = runQueryForOne($conn, "SELECT categoryID FROM category WHERE name = '{$category}' LIMIT 1");
  
	if($result === false) {
    $sql = "SELECT Max(formOrder) as M FROM category";
    $nextOrder = runQueryForOne($conn, $sql)['M'] + 1;
		$sql = "INSERT INTO category (name, small, medium, large, isDeleted, formOrder)
				VALUES ('$category', 0, 0, 0, 0, " . $nextOrder . ")";
		if (queryDB($conn, $sql) === TRUE) {
			$sql = "SELECT DISTINCT name, categoryID FROM category WHERE name = '$category'";
			$result = queryDB($conn, $sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$categoryID = $row["categoryID"];
				}
			}
		}
		else{
			createCookie("errUpdate", 1, 30);
      closeDB($conn);
			redirectPage("ap_io7.php");
		}
	}
	else {
		$categoryID = $result["categoryID"];
	}

   // Update our item appropriately
	$sql = "UPDATE item
			SET categoryID  = " . $categoryID . ",  itemName = '" . $itemName . "',
				displayName = '" . $displayName . "', price = " . $price . ",
			  small = " . $small . ", medium = " . $medium . ",
				large = " . $large . ", aisle = " . $aisle . ", rack = " . $rack . ", shelf = " . $shelf . "
			WHERE itemID = " . $itemID;

    if (queryDB($conn, $sql) === TRUE) {
		createCookie("itemUpdated", 1, 30);
		redirectPage("ap_io7.php");
    }
	else {
    die("sql: " . $sql . "<br>Err: " . sqlError($conn));
    createCookie("errUpdate", 1, 30);
    redirectPage("ap_io7.php");
  }

  closeDB($conn);
}
elseif (isset($_GET['ReactivateItem'])) {
    $conn = connectDB();
    if ($conn->connect_error) {
      createCookie("errConnection", 1, 30);
      redirectPage("ap_io7.php");
    }

    $itemID = $_GET['itemID'];
    $categoryName = $_GET['categoryID'];

    $sql = "UPDATE item SET isDeleted = 0 WHERE itemID=$itemID";

    if (queryDB($conn, $sql) === TRUE) {
      $sql = "UPDATE category SET isDeleted = 0 WHERE categoryID='$categoryID'";

      if (queryDB($conn, $sql) === TRUE) {
        createCookie("itemReactivated", 1, 30);
      }
      else{
        createCookie("err_itemReactivated2", 1, 30);
      }
    }
    else{
      createCookie("err_itemReactivated1", 1, 30);
    }
    closeDB($conn);
    redirectPage("ap_io7.php?showDeleted=1");

}


?>