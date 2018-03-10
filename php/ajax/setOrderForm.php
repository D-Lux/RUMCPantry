<?php
	include('../utilities.php');

	// AJAX call to update item quantity
	if (isset($_GET['newQty'])) {
		// Grab our GET Data
		$itemID = $_GET['itemID'];
		$newQty = $_GET['newQty'];
		$familyType = $_GET['familyType'];

		$conn = connectDB();
		if ($conn->connect_error) {
			die("There was an error attempting to update");
		}
		$updateItem = 	"UPDATE item
                     SET " . $familyType . "=" . $newQty . "
                     WHERE itemID=" . $itemID;

    if (queryDB($conn, $updateItem)) {
      $itemInfo = runQueryForOne($conn, "SELECT itemName FROM item WHERE itemID = {$itemID}");
      echo $itemInfo['itemName'] . " " . $familyType . " quantity updated to " . $newQty . ".";
    }
    else {
      echo "There was an error attempting to update";
    }
	}

	// AJAX Call to update item factor
	if (isset($_GET['newFx'])) {

		// Grab our GET Data
		$itemID = $_GET['itemID'];
		$newFX = $_GET['newFx'];

		$conn = connectDB();
		if ($conn->connect_error) {
			die("There was an error attempting to update");
		}
		$updateItem = 	"UPDATE item
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

		$conn = connectDB();
		if ($conn->connect_error) {
			die("There was an error attempting to update");
		}
		$updateCategory = 	"UPDATE category
							SET " . $familyType . "=" . $cQty . "
							WHERE categoryID=" . $CID;
		if (queryDB($conn, $updateCategory) === FALSE) {
			echo "sql error: " . mysqli_error($conn);
		}

    if (queryDB($conn, $updateCategory)) {
      $catInfo = runQueryForOne($conn, "SELECT name FROM category WHERE categoryID = {$CID}");
      echo $catInfo['name'] . " " . $familyType . " selection quantity updated to " . $cQty . ".";
    }
    else {
      echo "There was an error attempting to update";
    }


		//echo "Query: " . $updateCategory;
	}

	closeDB($conn);
?>