<html>
<head>
    <script src="js/utilities.js"></script>
	<script src="js/orderFormOps.js"></script>
	<?php include 'php/utilities.php';
		  include 'php/checkLogin.php';?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />


</head>
<body>
	<button onclick="goBack()">Go Back</button>
	<h1>Order Form: Specials</h1>
	<?php
	
	// TODO: build old order form from text file

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
		echo "<div id='newItems'></div>";
			
		echo "<br>";
		// Button to create a new set of special options
		echo "<input type='button' value='New Item' onclick='addSpecialItem()'><br>";
		
		// Save button and close the form
		echo "<input type='submit' name='CreateInvoiceDate' value='Save'></form>";
		
		// Error log for debug
		//echo "<br><br><br>";
		//echo "<div id='errorLog'> </div>";
	?>
	
	
</body>

</html>