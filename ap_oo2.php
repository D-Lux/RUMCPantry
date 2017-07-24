<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<script src="js/orderFormOps.js"></script>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css"/>
	<link rel="stylesheet" type="text/css" href="css/tabs.css" />
	<?php include 'php/utilities.php'; ?>
    <title>Order Form Configuration</title>
	
</head>

<body>
    <button onclick="goBack()">Go Back</button>
	<h1>
		Order Form: 
	<?php 
		$familyType = "";
		// Family Size 1-2 and Walk-Ins
		if (isset($_GET["1to2"])) { echo "One to Two / Walk-In"; $familyType = "small"; }
		// Family Size 3-4
		if (isset($_GET["3to4"])) { echo "Three to Four"; $familyType = "medium"; }
		// Family Size 5+
		if (isset($_GET["5Plus"])) { echo "Five+"; $familyType = "large"; }
		
		if ($familyType === ""){
			header("location: /RUMCPantry/ap_oo1.php");
		}
		
		$familyToken = substr($familyType,0,1);
		
		echo "</h1>";
		echo "<br><div id='ErrorLog'></div>";
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// * Query the database
		
		// Create our query from the Item and Category tables
		// Item Name, factor, $familyType, category name, category family type, itemid
		$sql = "SELECT itemID, rack, shelf, aisle, itemName, Item." . $familyType . " as IQty, 
					Category.name as CName, Category." . $familyType . " as CQty, Item.categoryID,
					Count(Category.name) as CCount					
				FROM Item
				JOIN Category
				ON Item.categoryID=Category.categoryID
				WHERE item.isDeleted=0
				AND Category.isDeleted=0
				AND Category.name<>'Specials'
				AND Category.name<>'redistribution'
				GROUP BY itemID
				ORDER BY Category.name, aisle, rack, shelf, itemName";

		$itemList = queryDB($conn, $sql);
		
		if ($itemList === FALSE) {
			// Assignment failed, error back
			echo "sql error: " . mysqli_error($conn);
			closeDB($conn);
			echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
			echoDivWithColor("Bad SQL query.", "red" );
		}
		
		// *********************************************
		// * Create our tabs
		
		$sql = "SELECT DISTINCT name 
				FROM Category
				ORDER BY name";
		$numTabs = queryDB($conn, $sql);
		
		echo "<div class='tab'>";
		for ($i=0; $i< $numTabs->num_rows; $i++) {
			$tab = sqlFetch($numTabs);
			echo "<button class='tablinks' onclick='viewTab(event, itemList" . $i . ")'";
			echo ($i > 0 ? "" : " id='defaultOpen' ") . ">" . $tab['name'] . "</button>";
		}
		echo "</div>";
		
		// ******************************************************
		// ** Create our table of information

		$currCategory = "";
		$tabCounter = 0;
		while ($item = sqlFetch($itemList)) {
			// if this is a new category
			if ($currCategory != $item['CName']) {
				// If we were in a different category, close off that category's table
				if ($currCategory != "") {
					echo "</table>";
					echo "</div>";
				}
				// Print out the category name, followed by the selection qty and start a new table
				//echo $item['CName'] . "<br>"; // The category name is in the tab name now
				echo "<div id='itemList" . $tabCounter . "' class='tabcontent'>";
				$tabCounter++;
				echo "Selection Quantity: ";
				/*	// Text field version
				echo "<input id='cid" . $familyToken . $item['categoryID'] . "' type='number' min=0 
						value=" . $item['CQty'] . " onchange='AJAX_UpdateCQty(this)'><br>";
				*/
				echo "<select id='cid" . $familyToken . $item['categoryID'] . "' onchange='AJAX_UpdateCQty(this)'>";
				for ($i = 0; $i < 9; $i++) {
					echo "<option value=$i " . ($i == $item['CQty'] ? "Selected" : "") . ">$i</option>";
				}
				echo "</select>";
				
				$currCategory = $item['CName'];
				echo "<table><tr><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Qty</th></tr>";
			}
			// Print this item's name and show it's location
			echo "<tr><td>" . $item['itemName'] . "</td>";
			echo "<td>" . aisleDecoder($item['aisle']) . "</td>";
			echo "<td>" . $item['rack'] . "</td>";
			echo "<td>" . $item['shelf'] . "</td>";
			
			// *****************************************
			// This is the dropdown selection list
			echo "<td><select id='iqty" . $familyToken . $item['itemID'] . "' onchange='AJAX_UpdateQty(this)'>";
			
			for ($i = 0; $i < 11; $i++) {
				echo "<option value=$i " . ($i == $item['IQty'] ? "Selected" : "") . ">$i</option>";
			}
			echo "</select></td>";
			
			/*	// Text field version
			echo "<td><input id='iqty" . $familyToken . $item['itemID'] . "' type='number' min=0 
						value=" . $item['IQty'] . " onchange='AJAX_UpdateQty(this)'></td>";
			*/
		}
		echo "</table>";
		echo "</div>";
		//echo "<br><div id='ErrorLog'></div>";
	?>
	
	<script>
		// Open the default tab (if tabs exist)
		document.getElementById("defaultOpen").click();
	</script>

</body>

</html>