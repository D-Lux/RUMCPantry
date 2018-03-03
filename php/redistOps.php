<?php

include 'utilities.php';
//debugEchoPOST();debugEchoGET();
// Go to specific client/member pages based on buttons pressed


if (isset($_GET['updateRedistItem'])) {
	header ("location: /RUMCPantry/ap_ro7.php?id=" . $_GET['id']);
}
elseif (isset($_POST['viewRedistribution'])) {
	header ("location: /RUMCPantry/ap_ro10.php?id=" . $_POST['id']);
}

// *******************************************************
// Start Redistribution Invoice operations
// *******************************************************

// --== Add new redistribution ==--
elseif(isset($_POST['submitRedistribution'])) {
	// POST: date | partnerID | itemID[] | qty[]
	// Debug text
	echo "<h1>Attempting to create a new redistribution invoice</h1>";
	foreach ( $_POST["itemID"] as $i=>$itemID ) {
		echo "ITEM ID: " . $itemID . " Count: " . $_POST["qty"][$i] . "<br>";
	}

	// *********************************************
	// * --== Create our item ID / Price array ==--

	// Connect to the database and grab Item ID and Price information
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Query String
	$sql = "SELECT itemID, price
			FROM Item
			WHERE Item.isDeleted=0";
	$itemPriceQuery = queryDB($conn, $sql);

	$PriceID_Array = array();
	while( $itemRow = sqlFetch($itemPriceQuery) ) {
		$PriceID_Array[$itemRow['itemID']] = $itemRow['price'];
	}

	// ******************************
	// * --== Create our Invoice ==--

	$redistDate = makeString($_POST['date']);
	$sql = "INSERT INTO Invoice (clientID, visitDate, status)
			VALUES (" . $_POST['partnerID'] . ", " . $redistDate . ", " . GetRedistributionStatus() . ")";


	if (queryDB($conn, $sql) === TRUE) {
		// Get our Invoice ID Key to use for the invoice descriptions
		$invoiceID = $conn->insert_id;

		// ***************************************
		// * --== Create invoice descriptions ==--

		// Start our query string
		$insertionSql = "INSERT INTO InvoiceDescription (invoiceID, itemID, quantity, totalItemsPrice, special ) VALUES ";
		$firstInsert = TRUE;

		$itemIDs = $_POST['itemID'];
		$itemQtys = $_POST['qty'];

		for ($i=0; $i < count($itemIDs); $i++) {
			// Get my values (makes the insertion query string cleaner)
			$currItem = $itemIDs[$i];
			$currQty = $itemQtys[$i];
			$totalPrice = $currQty * (isset($PriceID_Array[$currItem]) ? $PriceID_Array[$currItem] : 0);

			// Append to the insertion query string
			$insertionSql .= (!$firstInsert ? "," : "");	// Add a comma if we aren't the first insertion
			$insertionSql .= "( $invoiceID, $currItem, $currQty, $totalPrice, 0 )";
			$firstInsert = FALSE;
		}

		// Perform insertion
		if (queryDB($conn, $insertionSql) === TRUE) {
			createCookie("newRedistribution", 1, 30);
			header("location: /RUMCPantry/ap_ro8.php");
		}
		else {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to create Invoice Descriptions.", "red" );
		}
	}
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to create Invoice.", "red" );
	}
}

// --== Delete invoice ==--
elseif(isset($_POST['deleteRedistInvoice'])) {
	// Connect to the database and grab Item ID and Price information
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Query String
	$sql = "DELETE FROM Invoice
			WHERE invoiceID=" . $_POST['id'];

	if (queryDB($conn, $sql) === TRUE) {
		createCookie("redistributionDeleted", 1, 30);
		header("location: /RUMCPantry/ap_ro8.php");
	}
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, unable to delete invoice.", "red" );
	}
}
// *******************************************************
// Start Redistribution Client operations
// *******************************************************
// ************************************
// Submitting a new partner
elseif(isset($_POST['submitNewRedistPartner'])) {
	// Get form data
	$redistName   = "REDISTRIBUTION";
	$partnerName  = fixInput($_POST['partnerName']);
	$email 				= fixInput($_POST['email']);
	$address 			= $_POST['addressStreet'];
	$phoneNo 			= $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];
	$city 				= fixInput($_POST['addressCity']);
	$state 				= $_POST['addressState'];
	$zip 					= $_POST['addressZip'];
	$error        = "";
	$redistID     = 0;
	// *************************
	// * Validate form
	if (empty($partnerName)) {
    $error .= "<p>Name is required.</p>";
  }
  if (empty($phoneNo) && empty($email)) {
    $error .= "<p>Need either a phone number or an email.</p>";
  }

  // ************************
  // Continue if we're good
  if ($error == '') {
    $conn = connectDB();
    $sql = "INSERT INTO Client (numOfAdults, NumOfKids, email, phoneNumber,	address, city, state, zip, foodStamps, isDeleted, redistribution)
						VALUES (0,0, '{$email}', '{$phoneNo}', '{$address}', '{$city}', '{$state}', '{$zip}', FALSE, FALSE, TRUE)";

    if (queryDB($conn,$sql) === FALSE) {
      $error = "There was an error connecting to the database, please try again later.<br>" . $sql . "<br>" . sqlError($conn);
    }
    else {
    	// Get the ID of the partner we just created (we will need it to create the name)
    	$redistID = $conn->insert_id;
    	// Create the insert string and perform the insertion
			$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, clientID, isDeleted)
					VALUES ('{$redistName}', '{$partnerName}', TRUE, {$redistID}, FALSE)";
			if (queryDB($conn, $sql) === FALSE) {
				queryDB($conn, "DELETE FROM Client WHERE clientID = {$redistID}");
				$error = "There was an error connecting to the database, please try again later.<br>" . $sql . "<br>" . sqlError($conn);
			}
    }
    closeDB($conn);
  }
  die( json_encode(array("error" => $error, "id" => $redistID)));
}


// ***********************************
// Updating a partner
elseif(isset($_POST['submitUpdateRedist'])) {
	// Get form data
	$partnerName  = fixInput($_POST['partnerName']);
	$email 				= fixInput($_POST['email']);
	$address 			= $_POST['addressStreet'];
	$phoneNo 			= $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];
	$city 				= fixInput($_POST['addressCity']);
	$state 				= $_POST['addressState'];
	$zip 					= $_POST['addressZip'];
	$redistID     = $_POST['submitUpdateRedist'];
	$notes				= fixInput($_POST['notes']);
	$error        = "";

	// *************************
	// * Validate form
	if (empty($partnerName)) {
    $error .= "<p>Name is required.</p>";
  }
  if (empty($phoneNo) && empty($email)) {
    $error .= "<p>Need either a phone number or an email.</p>";
  }

	// ************************
  // Continue if we're good
  if ($error == '') {
		// Create update string for basic data
		$dataUpdate =  "UPDATE Client
				SET	phoneNumber='{$phoneNo}', email='{$email}', notes='{$notes}', zip='{$zip}', state='{$state}', address='{$address}', city='{$city}'
				WHERE clientID = {$redistID}";
		// Create update string for name data
		$nameUpdate =  "UPDATE FamilyMember
				SET FamilyMember.lastName='{$partnerName}'
				WHERE clientID={$redistID}";

		$conn = connectDB();
		if ((queryDB($conn,$dataUpdate) === FALSE) || (queryDB($conn,$nameUpdate) === FALSE)) {
      $error = "There was an error connecting to the database, please try again later.<br>" . $sql . "<br>" . sqlError($conn);
    }
    closeDB($conn);
  }
  echo json_encode(array("error" => $error));
  //die( json_encode(array("error" => $error)));
}

// *******************************************************
// Start Redistribution Item operations
// *******************************************************

// ************************************
// Submitting a new redistribution item
elseif(isset($_POST['submitNewRedistItem'])) {
	$iName    = fixInput($_POST['itemName']);
	$price    = empty($_POST['price'])  ? (int)0 : $_POST['price'];
	$weight   = empty($_POST['weight']) ? (int)0 : $_POST['weight'];
  $category = getRedistributionCategory();
  $error    = "";
  $conn     = connectDB();
	// ***************
  // Validation:
  
  // Check if there is a name, and if it already exists
  if (empty($iName)) {
    $error = "<p>Name cannot be empty.</p>";
  }
  else {
    $sql = "SELECT itemID from item WHERE itemName = '{$iName}' AND categoryID = {$category}";
    if (runQueryForOne($conn, $sql)) {
      $error = "<p>That name already exists in the redistribution item list.</p>";
    }
  }
  
  if ($error == "") {
    $sql = "INSERT INTO Item
            (itemName, price, aisle, categoryID, rack, isDeleted, small, medium, large, shelf )
            VALUES
            ('{$iName}', {$price}, {$weight}, {$category}, -1, 0, 0, 0, 0, 0)";

    // Perform and test insertion
    if (queryDB($conn, $sql) === FALSE) {
      $error = "<p>There was an error attempting to insert the new item.<br>Query: " . $sql . "<br>Error: " . sqlError($conn);
    }
  }
  
  closeDB($conn);
  echo json_encode(array("error" => $error));
}


// ***********************************
// Updating a redistribution item
elseif(isset($_POST['submitUpdateRedistItem'])) {
	$iName    = fixInput($_POST['itemName']);
  $itemID   = $_POST['submitUpdateRedistItem'];
	$price    = empty($_POST['price'])  ? (int)0 : $_POST['price'];
	$weight   = empty($_POST['weight']) ? (int)0 : $_POST['weight'];
  $category = getRedistributionCategory();
  $error    = "";
  $conn     = connectDB();
	// ***************
  // Validation:
  
  // Check if there is a name, and if it already exists
  if (empty($iName)) {
    $error = "<p>Name cannot be empty.</p>";
  }
  else {
    $sql = "SELECT itemID from item 
            WHERE itemName = '{$iName}' 
            AND categoryID = {$category}
            AND itemID <> {$itemID}";
    if (runQueryForOne($conn, $sql)) {
      $error = "<p>That name already exists in the redistribution item list.</p>";
    }
  }
  
  if ($error == "") {
    $sql = "UPDATE Item 		
            SET itemName='{$iName}', price = {$price}, aisle = {$weight}
            WHERE itemID = {$itemID}";

    // Perform and test insertion
    if (queryDB($conn, $sql) === FALSE) {
      $error = "<p>There was an error attempting to update the item.<br>Query: " . $sql . "<br>Error: " . sqlError($conn);
    }
  }
  
  closeDB($conn);
  echo json_encode(array("error" => $error));
}

// ************************************************************************
// ** START TOGGLE OPTIONS ************************************************
// ***********************************
// Setting a partner to 'isDeleted'
elseif(isset($_GET['deleteRedist'])) {
	toggleRedistributionClient(1);
}
// ***********************************
// Setting a partner to 'isDeleted' FALSE (reactivating)
elseif(isset($_GET['activateRedist'])) {
	toggleRedistributionClient(0);
}
// ***********************************
// Setting a redistribution item to 'isDeleted'
elseif(isset($_GET['deleteRedistItem'])) {
	toggleRedistributionItem(1);
}
// ***********************************
// Setting a redistribution item to 'isDeleted' FALSE (reactivating)
elseif(isset($_GET['activateRedistItem'])) {
	toggleRedistributionItem(0);
}

// **************************************
// * Function for toggling redistribution isDeleted flags

function toggleRedistributionItem($isDeleted) { toggleRedistribution("Item", "itemID", $isDeleted); }
function toggleRedistributionClient($isDeleted) { toggleRedistribution("Client", "clientID", $isDeleted); }

function toggleRedistribution($table, $field, $isDeleted) {
	//debugEchoPOST();
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		// TODO: gracefully fail this
		die("Connection failed: " . $conn->connect_error);
	}

	$sql =  "UPDATE " . $table . "
					SET	isDeleted=" . $isDeleted . "
					WHERE " . $field . "="	. $_GET['id'];

	// Create the return page string
	$loc = "location: /RUMCPantry/ap_ro";
	$loc .= ($table=="Client" ? "2" : "5");
	$loc .= ($isDeleted==1 ?  ".php" : ".php?ShowInactive=1");

	// Perform and isDeleted setting
	if (queryDB($conn, $sql) === TRUE) {
		// Set the cookie appropriate for this function
		createCookie("redistToggled", 1, 30);
	}
	else {
		// TODO: Error message
		echo "error " . sqlError($conn);
	}
	closeDB($conn);

	header($loc);
}


// ***********************************************************************
// * "Redistribution" Category

// We need a custom category called REDISTRIBUTION
// This function finds the Category ID
function getRedistributionCategory() {
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Find the 'redistribution' category
	$sql = "SELECT categoryID
					FROM Category
					WHERE name ='Redistribution'";

	$redistCategory = queryDB($conn, $sql);
	closeDB($conn);

	// If we didn't get a match, we need to create the 'Redistribution' category
	if ( $redistCategory==null || $redistCategory->num_rows <= 0 ) {
		return createRedistributionCategory();
	}
	// If we found a match, close the database and return the category ID
	else {
		$getID = sqlFetch($redistCategory);
		return $getID['categoryID'];
	}
}

function createRedistributionCategory(){
	$conn = connectDB();
	if ($conn->connect_error) {
		// TODO: Fail this gracefully
		die("Connection failed: " . $conn->connect_error);
	}
	// Create insertion string
	$sql = "INSERT INTO Category (name, small, medium, large, isDeleted, formOrder)
			VALUES ('Redistribution',0,0,0,0, -1)";

	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		$retID = $conn->insert_id;
		closeDB($conn);
		// Return Get the ID Key of the category we just created
		return $retID;
	}
	else {
		closeDB($conn);
		// TODO Fail this gracefully
		return null;
	}
}
?>