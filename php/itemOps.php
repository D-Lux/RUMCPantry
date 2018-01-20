<script src="/RUMCPantry/js/utilities.js"></script>
<?php

include('utilities.php');

if(isset($_POST['createItem'])) {
    $category 	 = $_POST['category'];
    $itemName 	 = $_POST['itemName']; /*grab the name textbox*/
    $displayName = $_POST['displayName'];
    $price 		 = $_POST['price'];
    $small 		 = $_POST['small'];
    $medium 	 = $_POST['medium'];
    $large 		 = $_POST['large'];
    $aisle 		 = aisleEncoder($_POST['aisle']);
    $rack 		 = rackEncoder($_POST['rack']);
    $shelf 		 = shelfEncoder($_POST['shelf']);
    $categoryID  = null;
	$newCategory = FALSE;
	

    /* previous lines set up the strings for connextion*/
    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    //check to see if category exists, if not create it.
	$result = $conn->query("SELECT DISTINCT categoryID FROM Category WHERE name = '" . $category . "'");
	if($result->num_rows == 0) {
		$sql = "INSERT INTO category (name, small, medium, large, isDeleted)
				VALUES ('" . $category . "', 0, 0, 0, 0)";
		if ($conn->query($sql) === TRUE) {
			$newCategory = TRUE;
			$categoryID = $conn->insert_id;
        }
		else {
			closeDB($conn);
			createCookie("errCreate", 1, 30);
			header("location: /RUMCPantry/ap_io7.php");
		}
    } 
	else {
		$row = sqlFetch($result);
        $categoryID = $row["categoryID"];
    }


    $sql = "INSERT INTO item (itemName, displayName, price, timestamp, isDeleted, small, medium, large, categoryID, aisle, rack, shelf)
    VALUES ('$itemName', '$displayName', '$price', now(), 0, '$small', '$medium', '$large', '$categoryID', '$aisle', '$rack', '$shelf')";

    if ($conn->query($sql) === TRUE) {
		closeDB($conn);
		createCookie("newItem", 1, 30);
		header("location: /RUMCPantry/ap_io7.php");  
    } 
	else {
		if ($newCategory) {
			$conn->query("DELETE FROM Category WHERE categoryID = " . $categoryID);
		}
		closeDB($conn);
        createCookie("errCreate", 1, 30);
		header("location: /RUMCPantry/ap_io7.php");    
    }
}
elseif (isset($_GET['DeleteItem'])) {
	$itemID = $_GET['itemID'];
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $result = $conn->query("SELECT DISTINCT itemID FROM Item WHERE itemID = " . $itemID);
    if($result->num_rows > 0) {

        $sql = "update Item set isDeleted=true where itemID=" . $itemID;

         if ($conn->query($sql) === TRUE) {
            createCookie("itemDeleted", 1, 30);
			header("location: /RUMCPantry/ap_io7.php");
          }
          else{
            echoDivWithColor("Error, failed to connect to database at delete.", "red" );
          }
    }
   
}
elseif (isset($_POST['updateItemIndividual'])) {
    $itemID   	 = $_POST['itemID'];
	$category 	 = $_POST['category'];
    $itemName 	 = $_POST['itemName'];
    $displayName = $_POST['displayName'];
    $price 		 = $_POST['price'];
    $small 		 = $_POST['small'];
    $medium 	 = $_POST['medium'];
    $large 		 = $_POST['large'];
    $aisle 		 = $_POST['aisle'];
    $rack 		 = $_POST['rack'];
    $shelf 		 = $_POST['shelf'];

    $categoryID = null;

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
		createCookie("errConnection", 1, 30);
		header("location: /RUMCPantry/ap_io7.php"); 
    } 


    //check to see if category ecists, if not create it.
    $result = $conn->query("SELECT DISTINCT name FROM Category WHERE name = '$category'");
	if($result->num_rows == 0) {
		$sql = "INSERT INTO category (name, small, medium, large)
				VALUES ('$category', 0, 0, 0, 0)";
		if ($conn->query($sql) === TRUE) {
			$sql = "SELECT DISTINCT name, categoryID FROM Category WHERE name = '$category'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$categoryID = $row["categoryID"];
				}
			} 
		}
		else{
			createCookie("errUpdate", 1, 30);
			header("location: /RUMCPantry/ap_io7.php"); 
		}
	} 
	else {
		$sql = "SELECT DISTINCT name, categoryID FROM Category WHERE name = '$category'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$categoryID = $row["categoryID"];
			}
			
		}
	}

	$sql = "UPDATE Item 
			SET categoryID  = " . $categoryID . ",  itemName = '" . $itemName . "', 
				displayName = '" . $displayName . "', price = " . $price . ", 
				timestamp = now(), small = " . $small . ", medium = " . $medium . ", 
				large = " . $large . ", aisle = " . $aisle . ", rack = " . $rack . ", shelf = " . $shelf . "  
			WHERE itemID = " . $itemID;

    if ($conn->query($sql) === TRUE) {
		createCookie("itemUpdated", 1, 30);
		header("location: /RUMCPantry/ap_io7.php");
    } 
	else {
        createCookie("errUpdate", 1, 30);
		header("location: /RUMCPantry/ap_io7.php"); 
    }

    $conn->close();
}
elseif (isset($_GET['DeleteCategory'])) {
  $conn = createPantryDatabaseConnection();
  if ($conn->connect_error) {
    createCookie("conErr", 1, 30);
    header("location: /RUMCPantry/ap_io8.php");
  } 
  $categoryID = $_GET['categoryID']; 
  $sql = "UPDATE Category SET isDeleted = true WHERE categoryID=" . $categoryID;
    
	if ($conn->query($sql) === FALSE) {
    createCookie("delError", 1, 30);
  }
  else {
    $sql = "UPDATE item SET isDeleted = true WHERE categoryID=$categoryID";
    if ($conn->query($sql) === FALSE) {
      createCookie("delError", 1, 30);
    }
    else{
      createCookie("categoryDeleted", 1, 30);
    }
  }
  header("location: /RUMCPantry/ap_io8.php");
}
elseif (isset($_GET['ReactivateCategory'])) {
  $conn = createPantryDatabaseConnection();
  if ($conn->connect_error) {
    createCookie("conErr", 1, 30);
  }

	$sql = "UPDATE Category SET isDeleted = false WHERE categoryID=" . $_GET['categoryID'];

  if ($conn->query($sql) === FALSE) {
    createCookie("reactivateError", 1, 30);
  }
  else{
    createCookie("categoryReactivated", 1, 30);
  }
  header("location: /RUMCPantry/ap_io8.php?showDeleted=1");
}
elseif (isset($_GET['ReactivateItem'])) {
    $conn = createPantryDatabaseConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
	
    $itemID = $_GET['itemID'];
    $categoryName = $_GET['categoryID'];   

    $sql = "UPDATE Item SET isDeleted = 0 WHERE itemID=$itemID";

    if ($conn->query($sql) === TRUE) {
      $sql = "UPDATE Category SET isDeleted = 0 WHERE categoryID='$categoryID'";
      
      if ($conn->query($sql) === TRUE) {
        createCookie("itemReactivated", 1, 30);
      }
      else{
        createCookie("err_itemReactivated2", 1, 30);
      }
    }
    else{
      createCookie("err_itemReactivated1", 1, 30);
    }
    header("location: /RUMCPantry/ap_io7.php?showDeleted=1"); 
    
}
elseif (isset($_POST['UpdateCategoryIndividual'])) {
    $categoryID = $_POST['categoryID'];
    $name 	 	= $_POST['category'];
    $small 		= $_POST['small']; 
    $medium 	= $_POST['medium'];
    $large 		= $_POST['large'];

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    //check to see if category exists, if not create it.
    $sql = "UPDATE Category
			SET categoryID = "  . $categoryID . ", 
				name 	   = '" . $name       . "', 
				small      = "  . $small      . ", 
				medium     = "  . $medium     . ", 
				large      = "  . $large      . " 
			WHERE categoryID = $categoryID";


    if ($conn->query($sql) === TRUE) {
		createCookie("categoryUpdated", 1, 30);
		header("location: /RUMCPantry/ap_io8.php"); 
    } 
	else {
        createCookie("errUpdate", 1, 30);
		header("location: /RUMCPantry/ap_io8.php");   
    }

    $conn->close();
}
elseif (isset($_POST['createCategory'])) {
    $name 	= $_POST['category'];
    $small 	= $_POST['small']; 
    $medium = $_POST['medium'];
    $large 	= $_POST['large'];

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT INTO Category (name, small, medium, large, isDeleted)
			VALUES ('" . $name . "', " . $small . ", " . $medium . ", " . $large . ", 0)";

    if ($conn->query($sql) === TRUE) {
		createCookie("newCategory", 1, 30);
		header("location: /RUMCPantry/ap_io8.php");
    } else {
        createCookie("errCreate", 1, 30);
		header("location: /RUMCPantry/ap_io8.php");       
    }
    $conn->close();
}

?>