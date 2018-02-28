<script src="/RUMCPantry/js/utilities.js"></script>

<?php

include 'utilities.php';
debugEchoPOST();debugEchoGET();
// Go to specific client/member pages based on buttons pressed
if (isset($_POST['newRedist'])) {
	header ("location: /RUMCPantry/ap_ro3.php");
}
elseif (isset($_POST['updateRedist'])) {
	header ("location: /RUMCPantry/ap_ro4.php?id=" . $_POST['id']);
}
elseif (isset($_POST['newRedistItem'])) {
	header ("location: /RUMCPantry/ap_ro6.php");
}
elseif (isset($_POST['updateRedistItem'])) {
	header ("location: /RUMCPantry/ap_ro7.php?id=" . $_POST['id']);
}
elseif (isset($_POST['newRedistInvoice'])) {
	header ("location: /RUMCPantry/ap_ro9.php");
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
	echo "<h1>Attempting to create a new partner</h1>";

	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	$email = makeString($_POST['email']);
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	
	// Family Member Fields
	$clientFirstName = makeString("REDISTRIBUTION");
	$clientLastName = makeString(fixInput($_POST['partnerName']));

	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create insertion string
	$sql = "INSERT INTO Client 
			(numOfAdults, NumOfKids, timestamp, email, phoneNumber, 
				address, city, state, zip, foodStamps, isDeleted, redistribution)
			VALUES 
			(0,0,now(),$email,$phoneNo,
				$address,$city,$state,$zip, FALSE, FALSE, TRUE)";
	
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		// Get the ID Key of the client we just created (we will need it to create the family member)
		$clientID = $conn->insert_id;
		// Create the insert string and perform the insertion
		$sql = "INSERT INTO FamilyMember (firstName, lastName, isHeadOfHousehold, clientID, timestamp, isDeleted)
				VALUES ($clientFirstName, $clientLastName, TRUE, $clientID, now(), FALSE)";
		if (queryDB($conn, $sql) === TRUE) {
			closeDB($conn);
			createCookie("newPartner", 1, 30);
			header("location: /RUMCPantry/ap_ro4.php?id=$clientID");
		}
		else {
			// delete the blank client we just made
			$sql = "DELETE FROM Client
					WHERE clientID = $clientID";
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to create family member.", "red" );
			
			if (queryDB($conn, $sql) === FALSE) {
				// This is a very bad error (created a blank client and couldn't remove it)
				echoDivWithColor("<h1>VERY BAD ERROR</h1>Check with developer. - ID: " . $clientID, "red" );
			}	
		}
	} 
	else {
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to connect to database.", "red" );	
	}
	closeDB($conn);
}


// ***********************************
// Updating a partner
elseif(isset($_POST['submitUpdateRedist'])) {
	// grab and fix post data
	$address = makeString(fixInput($_POST['addressStreet']));
	$city = makeString(fixInput($_POST['addressCity']));
	$state = makeString($_POST['addressState']);
	$zip = makeString($_POST['addressZip']);
	$partnerID = $_POST['partnerID'];
	$phoneNo = storePhoneNo($_POST['phoneNo']);
	$email = makeString($_POST['email']);
	$notes = makeString($_POST['notes']);
	$partnerName = makeString(fixInput($_POST['partnerName']));
		
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create update string for basic data
	$dataUpdate =  "UPDATE Client 
					SET	phoneNumber=$phoneNo, email=$email, notes=$notes, timestamp=now(),
						zip=$zip, state=$state, address=$address, city=$city
					WHERE clientID = $partnerID";
	// Create update string for name data
	$nameUpdate =  "UPDATE FamilyMember
					SET FamilyMember.lastName=$partnerName, timestamp=now()
					WHERE clientID=$partnerID";
	
	// Perform and test updates
	if (queryDB($conn, $dataUpdate) === TRUE) {
		if (queryDB($conn, $nameUpdate) === TRUE) {
			closeDB($conn);
			createCookie("updatePartner", 1, 30);
			header("location: /RUMCPantry/ap_ro2.php");
		}
		else {
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Error description: " . mysqli_error($conn), "red");
			echoDivWithColor("Error, failed to update.", "red" );	
		}		
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
	closeDB($conn);
}

// *******************************************************
// Start Redistribution Item operations
// *******************************************************

// ************************************
// Submitting a new redistribution item
elseif(isset($_POST['submitNewRedistItem'])) {
	echo "<h1>Attempting to create a new redistribution item</h1>";

	$iName = makeString(fixInput($_POST['itemName']));
	$price = ($_POST['price']!= "" ? $_POST['price'] : 0);
	$weight = ($_POST['weight']!= "" ? $_POST['weight'] : 0);
	
	$category = getRedistributionCategory();
	echo "RedistID = " . $category . "<br>";
	
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	// Create insertion string (set the weight to aisle and -1 to the rack to distinguish from normal items)
	$sql = "INSERT INTO Item 
			(itemName, price, aisle, categoryID, timestamp, rack, isDeleted, small, medium, large, shelf )
			VALUES 
			($iName, $price, $weight, $category, now(), -1, 0, 0, 0, 0, 0)";
	echo "query: " . $sql . "<br>";
	// Perform and test insertion
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		createCookie("newRedistItem", 1, 30);
		header("location: /RUMCPantry/ap_ro5.php");
	} 
	else {
		closeDB($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to add new item.", "red" );	
	}
}


// ***********************************
// Updating a redistribution item
elseif(isset($_POST['submitUpdateRedistItem'])) {
	$iName = makeString(fixInput($_POST['itemName']));
	$price = ($_POST['price']!= "" ? $_POST['price'] : 0);
	$weight = ($_POST['weight']!= "" ? $_POST['weight'] : 0);
	$itemID =$_POST['id'];
		
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create update string for basic data
	$dataUpdate =  "UPDATE Item 
					SET	itemName=$iName, price=$price, timestamp=now(), small=$weight
					WHERE itemID = $itemID";
	
	// Perform and test updates
	if (queryDB($conn, $dataUpdate) === TRUE) {
		closeDB($conn);
		createCookie("redistItemUpdated", 1, 30);
		header("location: /RUMCPantry/ap_ro5.php");		
	}
	else {
		closeDB($conn);
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to update.", "red" );	
	}
}

// ************************************************************************
// ** START TOGGLE OPTIONS ************************************************
// ***********************************
// Setting a partner to 'isDeleted'
elseif(isset($_POST['deleteRedist'])) {
	toggleRedistributionClient(1);
}
// ***********************************
// Setting a partner to 'isDeleted' FALSE (reactivating)
elseif(isset($_POST['activateRedist'])) {
	toggleRedistributionClient(0);
}
// ***********************************
// Setting a redistribution item to 'isDeleted'
elseif(isset($_POST['deleteRedistItem'])) {
	toggleRedistributionItem(1);
}
// ***********************************
// Setting a redistribution item to 'isDeleted' FALSE (reactivating)
elseif(isset($_POST['activateRedistItem'])) {
	toggleRedistributionItem(0);
}

else {
	echo "<h1>Nothing was set</h1><br>";
	debugEchoPOST();debugEchoGET();
	//header("location: /RUMCPantry/mainpage.php");
}

// **************************************
// * Function for toggling redistribution isDeleted flags

function toggleRedistributionItem($isDeleted) { toggleRedistribution("Item", "itemID", $isDeleted); }
function toggleRedistributionClient($isDeleted) { toggleRedistribution("Client", "clientID", $isDeleted); }

function toggleRedistribution($db, $field, $isDeleted) {
	debugEchoPOST();
	// Set up server connection
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql =  "UPDATE " . $db . "
			SET	isDeleted=" . $isDeleted . "
			WHERE " . $field . "="	. $_POST['id'];

	// Perform and isDeleted setting
	if (queryDB($conn, $sql) === TRUE) {
		closeDB($conn);
		// Set the cookie appropriate for this function
		createCookie("redistToggled", 1, 30);
		
		// Create the return page string
		$loc = "location: /RUMCPantry/ap_ro";
		$loc .= ($db=="Client" ? "2" : "5");
		$loc .= ($isDeleted==1 ? ".php" : "i.php");
		
		// Return to the correct page
		header ($loc);
	}
	else {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error, failed to set redistribution state.", "red" );
		closeDB($conn);
	}
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
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
		echoDivWithColor("Error description: " . mysqli_error($conn), "red");
		echoDivWithColor("Error, failed to create Redistribution category.", "red" );	
	}
}
?>