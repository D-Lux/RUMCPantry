<?php include 'php/header.php'; ?>
<script src="js/redistOps.js"></script>

	<button id='btn_back' onclick="goBack()">Back</button>
    <h3>New Reallocation</h3>
	
	<div class="body_content">
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// ***************************************
		// * Partner data list
		
		// get our Redistribution Partner information and store it as a datalist
		$sql = "SELECT Client.clientID as ID, city, FamilyMember.lastName as name
				FROM Client
				JOIN FamilyMember
				ON Client.clientID=FamilyMember.clientID
				WHERE Client.redistribution=1 
				AND FamilyMember.FirstName='REDISTRIBUTION'
				AND Client.isDeleted=0";
				
		$partnerData = queryDB($conn, $sql);
		
		// Create our Data List of partners (including hidden ID values)	
		$partnerDataList = null;
		if ($partnerData!=null && $partnerData->num_rows > 0) {
			// Javascript onChange will alter the value of hidden input with ID="partnerID-hidden"
			$partnerDataList = "<input type='text' name='partnerName' list='Partners' autocomplete='off' id='partnerID'";
			$partnerDataList .= " onchange='updateHiddenID(this)'><datalist id='Partners' >";
			// loop through the query results to create a data list
			while($partner = sqlFetch($partnerData) ) {
				$partnerDataList .= "<option data-value=" . $partner['ID'] . ">";
				$partnerDataList .= $partner['name'];
				if ($partner['city'] != "") {
					$partnerDataList .= " - " . $partner['city'];
				}
				$partnerDataList .= "</option>";
			}
			$partnerDataList .= "</datalist>";
		}
		else {
			echo "No redistribution partners available.";
		}
		
		// ************************
		// * Item Datalist
		
		// get our Redistribution Partner information and store it as a datalist
		$sql = "SELECT itemID, itemName
				FROM Item
				WHERE rack=-1
				AND isDeleted=0";
				
		$itemData = queryDB($conn, $sql);
		
		// Create our Data List of items and hidden id values
		$itemDataList = null;
		if ($itemData!=null && $itemData->num_rows > 0) {
			// loop through the query results to create a data list
			while($item = sqlFetch($itemData) ) {
				$itemDataList .= "<option data-value=" . $item['itemID'] . ">";
				$itemDataList .= $item['itemName'] . "</option>";
			}
			$itemDataList .= "</datalist>";
		}
		else {
			echo "No redistribution items available.";
		}
		
		// Close off the database connection because we no longer need it
		closeDB($conn);
		
		// **************************************************************************
		// ** Hidden Div that gets copied when we add an item
		
		echo "<div id='addRedistItemTemplate' style='display:none;' >";
		echo "Item Name: <input type='text' list='Items' name='item[]' id='itemID_' ";
		echo " onchange='updateHiddenID(this)'><datalist id='Items' >";
		echo $itemDataList;
		
		// Hidden item ID
		echo "<input type='hidden' ID='itemID_-hidden' name='itemID[]' value=-1>";
		// Quantity of this item
		echo " Quantity: <input type='number' name='qty[]' value=1>"; // min=1 
		
		// Delete Button for a section
		echo "<input id='DelBtn_' type='button' value=' ' class='btn_trash' onclick='deleteRedistItem(this)' >";
		echo "<br>";
		echo "</div>";
		
		
		// *******************************
		// * FORM Start
		if (($partnerDataList != null) && ($itemDataList != null)){
			// Start the form
			echo "<form method='post' onSubmit='return validateRedistribution()' action='php/redistOps.php'>";
			
			// Get a date
			echo "Date: <input type='date' value='" . date('Y-m-d') . "' name='date' ><br>";
			
			// Fill in the partner
			echo "<div id='PartnerField'>Partner: " . $partnerDataList . "</div>"; // partnerID
			// Hidden input for the partner ID (javascript should update the value)
			echo "<input type='hidden' ID='partnerID-hidden' name='partnerID' value=-1>";
			echo "<br><br>";
			
			
			// Button to create a drop down for an item and quantity to add
			echo "<input type='button' value='Add Item' onclick='addRedistItem()'><br>";
			
			// Create the div that will hold items (from the hidden div above)
			echo "<div id='newItems'></div>";
			
			// Save button and close the form
			echo "<input type='submit' name='submitRedistribution' value='Save'></form>";
		}
	?>


	</div><!-- /body_content -->
	</div><!-- /content -->
</body>
</html>