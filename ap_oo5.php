<?php include 'php/utilities.php';?>
<script src="js/orderFormOps.js"></script>

	<button id='btn_back' onclick="goBack()">Back</button>
	<h3>Order Form: Specials</h3>
	
	<div class="body_content">
	<?php

		// *****************************************************
		// * Do our SQL query and store off a datalist of items
		
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
			
		// Get the last appointment date in the database
		$sql = "SELECT itemID, itemName
				FROM Item
				WHERE isDeleted<>TRUE
				ORDER BY itemName DESC";
		$result = queryDB($conn, $sql);
		// Close the database connection
		closeDB($conn);
		if ($result === FALSE) {
			die("No items in database");
		}
		
		// Create our Data list so we're not fetching through the SQL query every time
		$itemList = "<datalist id='Items'>";
		while($item = sqlFetch($result)) {
			$itemList .= "<option value='" . $item['itemName'] . "'</option>";
		}
		$itemList .= "</datalist>";
				
		// **************************************************************************
		// ** Hidden Div that gets copied for Specials Options
		
		// Create our hidden div that we will clone when the user requests a new option
		echo "<div id='specialTemplate' style='display:none;' >";
		echo "<input type='text' list='Items' name='item_[]' >";
		// Dump out the list we created on page load
		echo $itemList;
		
		// "OR" Button and div to add Special Options
		echo "<input id='OrBtn_' type='button' value='OR' onclick='addSpecialOrItem(this)' >";
		echo "<div id='OrSlot_'></div><br>";
		
		// Delete Button for a section
		echo "<input id='DelBtn_' type='button' value='Remove Specials' onclick='deleteSpecials(this)' >";
		echo "<br><hr>";
		echo "</div>";
		
		// *********************************
		// ** This div is cloned when an "OR" button is pressed
		echo "<div id='specialOrTemplate' style='display:none;' >";
		echo "<input type='text' list='Items' name='item_[]' >";
		echo $itemList;
		echo "</div>";

		// **************************************************************
		// ** Form Start
		
		// Start our actual form for creating the specials order form
		echo "<form id='SpecialsForm' name='Specials' action='php/orderOps.php' method='post'>";
		
		// Create the div that will hold item options
		echo "<div id='newItems'>";
		
		// *************************************************************
		// * load the save file here and add appropriate boxes as needed
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$specialsFile = fopen("specials.txt","r") or die("Unable to open specials!");
		
		$savedItemNum = 1;
		while(!feof($specialsFile)) {
			$itemLine = explode(",", fgets($specialsFile));
			if (sizeof($itemLine) > 1) {
				echo "<div id='savedSpecialSlot" . $savedItemNum . "'>";
				for ($i = 0; $i < sizeof($itemLine); $i++) {
					// Only create a box if we've grabbed a numeric value (the eol character appears in the array)
					if (is_numeric($itemLine[$i])) {
						$sql = "SELECT itemName
								FROM Item
								WHERE itemID='" . $itemLine[$i] . "'
								LIMIT 1";
						$itemQuery = queryDB($conn, $sql);
						
						if ($itemQuery == NULL || $itemQuery->num_rows <= 0){
							echo $itemLine[$i] . "<br>";
							echo "sql error: " . mysqli_error($conn);	
						}
						else {
							$itemInfo = sqlFetch($itemQuery);
							echo "<input type='text' list='Items' value='" . $itemInfo['itemName'] . "'
									name='savedItem" . $savedItemNum . "[]' >";
							echo $itemList;
							echo "<br>";
						}
					}
				}
				// Delete Button
				echo "<input id='SavedDelBtn" . $savedItemNum . "' type='button' value='Remove Specials' 
						onclick='deleteSavedSpecials(this)' >";
				echo "<br><hr>";
				
				// Close off the div
				echo "</div>";
				
				$savedItemNum++;
			}
		}
		closeDB($conn);
		
		// ************************************************************************************************

		echo "</div>";
			
		echo "<br>";
		// Button to create a new set of special options
		echo "<input type='button' value='New Item' onclick='addSpecialItem()'><br>";
		
		// Save button and close the form
		echo "<input type='submit' name='SaveSpecials' value='Save'></form>";

		// Error log for debug
		echo "<br><br><br>";
		echo "<div id='errorLog'> </div>";
	?>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
	
	
</body>

</html>