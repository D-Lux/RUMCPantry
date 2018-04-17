<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
echo "<input type='hidden' value=" . $_POST['clientID'] . " name='clientID'>";
echo "<input type='hidden' value=" . $_POST['invoiceID'] . " name='invoiceID'>";
echo "<input type='hidden' value=" . $walkIn . " name='walkInStatus'>";

// Set defaults
$currCategory = "";

// *************************************************
// * Normal Items

// Roll through the items and create the order form
while ($item = sqlFetch($itemList)) {
	// Skip medicine if we're 
	if ( showCategory($walkIn, $item['CName']) ){
		if ($currCategory != $item['CName']) {
			echo "<h3>" . $item['CName'] . "</h3>";
			// Create a special div so the client can see an updated count of selected items
			echo "<h4><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] . 
				" (" . ($item['CQty']) . " remaining)</div></h4>";
			// Include hidden values so we can track the category
			echo "<input type='hidden' value=" . $item['CQty'] . " id=" . $item['CName'] . ">";
			$currCategory = $item['CName'];
			$slotID = 0;
		}

		// Display the Item name
		echo $item['displayName'];
		for ($i = 0; $i < $item['IQty']; $i++) {
			$slotID++;
			// Value is the item's ID
			// Name is the item's category[] (in array)
			echo "<input type='checkbox' value=" . $item['itemID'];
			echo " onclick='countOrder(this)' name='" . $item['CName'] . "[]' ";
					
			// If this item was selected, check it and reduce our count
			if ( ISSET($clientOrder[$item['itemID']]) ) {
				if ($clientOrder[$item['itemID']] > 0) {
					$clientOrder[$item['itemID']]--;
					echo " checked ";
				}
			}
			// Close off the html input tag
			echo ">";
		}
		echo "<br>";
	}
}

// *************************************************
// * Specials

if (!$walkIn) {
	$specialsFile = fopen("specials.txt","r") or die();
	echo "<hr><h2>Specials</h2><h3>Please select one from each section</h3><hr>";
	$conn = connectDB();
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$specialItemNum = 1;
	while(!feof($specialsFile)) {
		$itemLine = explode(",", fgets($specialsFile));
		if (sizeof($itemLine) > 1) {
			for ($i = 0; $i < sizeof($itemLine); $i++) {
				// Only create a box if we've grabbed a numeric value (the eol character appears in the array)
				if (is_numeric($itemLine[$i])) {
					$sql = "SELECT displayName
							FROM item
							WHERE itemID='" . $itemLine[$i] . "'
							LIMIT 1";
					$itemQuery = queryDB($conn, $sql);
					
					if ($itemQuery == NULL || $itemQuery->num_rows <= 0){
						echo "sql error: " . mysqli_error($conn);	
					}
					else {
						$itemInfo = sqlFetch($itemQuery);
						echo "<input type='radio' value=" . $itemLine[$i];
						echo " name='savedItem" . $specialItemNum . "' ";
						
						// Check if the item was selected
						if ( ISSET($clientSpecials[$itemLine[$i]]) ) {
							if ($clientSpecials[$itemLine[$i]] > 0) {
								$clientSpecials[$itemLine[$i]]--;
								echo " checked ";
							}
						}
						echo " >" . $itemInfo['displayName'];

						echo "<br>";
					}
				}
			}
			$specialItemNum++;
		}
		// Put some sort of separator between specials
		echo "<hr>";
	}
	closeDB($conn);
}


?>