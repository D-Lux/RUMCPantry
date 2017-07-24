<?php
	include('../utilities.php');
	
	// AJAX call to update item quantity
	if (isset($_GET['newQty'])) {
		// Grab our GET Data
		$itemID = $_GET['itemID'];
		$newQty = $_GET['newQty'];
		$familyType = $_GET['familyType'];

		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			echo "ERROR";
		}
		$updateItem = 	"UPDATE Item
						 SET " . $familyType . "=" . $newQty . "
						 WHERE itemID=" . $itemID;
		if (queryDB($conn, $updateItem) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
		}
		//debugEchoGET();
		echo "Query: " . $updateItem;
	}
	
	// AJAX Call to update item factor
	if (isset($_GET['newFx'])) {
		
		// Grab our GET Data
		$itemID = $_GET['itemID'];
		$newFX = $_GET['newFx'];

		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$updateItem = 	"UPDATE Item
						 SET factor=" . $newFX . "
						 WHERE itemID=" . $itemID;
		if (queryDB($conn, $updateItem) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
		}
		//echo "Query: " . $updateItem;
	}
	
	// AJAX Call to update category quantity
	if (isset($_GET['cQty'])) {
		// Grab our GET Data
		$CID = $_GET['CID'];
		$cQty = $_GET['cQty'];
		$familyType = $_GET['familyType'];

		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$updateCategory = 	"UPDATE Category
							SET " . $familyType . "=" . $cQty . "
							WHERE categoryID=" . $CID;
		if (queryDB($conn, $updateCategory) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
		}
		//echo "Query: " . $updateCategory;		
	}
	
	closeDB($conn);
?>