 <link rel="stylesheet" type="text/css" href="css/tabs.css" />
<?php

include 'itemOps.php';

$conn = createPantryDatabaseConnection();
    /* Check connection*/
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Rack set to -1 are strictly redistribution items
$sql = "SELECT itemID, itemName, displayName, price, item.small, item.medium, item.large, 
				category.name as cName, aisle, rack, shelf
		FROM item
		JOIN category
		ON category.categoryID=item.categoryID
		WHERE item.isDeleted=0
		AND (item.rack>=0
		OR item.rack IS NULL)";

$result = $conn->query($sql);

    // tabStarted lets us know we started a tab, but didn't close the /table form
	$tabStarted = FALSE;
		
	// loop through the query results
	if ($result!=null && $result->num_rows > 0) {
		// If tabsize has been updated from post, grab that, otherwise default to 20
		$TabSize = (isset($_POST['tabSize']) ? $_POST['tabSize'] : 10);

		$tabCounter = 0;
		$itemCounter = $TabSize;

		echo "<div class='tab'>";
		for ($i=0; $i< ceil($result->num_rows/$TabSize); $i++) {
			echo "<button class='tablinks' onclick='viewTab(event, itemList" . $i . ")'";
			echo ($i > 0 ? "" : "id='defaultOpen' ") . ">" . ($i + 1) . "</button>";
		}
		echo "</div>";

		while($row = sqlFetch($result)) {
			if ($itemCounter >= $TabSize) {
				$itemCounter = 0;
				$tabStarted = TRUE;
				// Create the item table and add in the headers
				echo "<table id='itemList" . $tabCounter . "' class='tabcontent'>";
				echo "<tr><th></th><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Category</th></tr>";						
				$tabCounter++;
			}
			$itemCounter++;
			
			// Start the form for this row (so buttons act correctly based on item)
			echo "<tr><form action=''>";
			echo "<input type='hidden' name='itemID' value='" . $row["itemID"] . "'>";
			echo "<td><input type='submit' name='UpdateItem' value='Edit'></td>";
			echo "<td>". $row["itemName"]. "</td><td>" . $row["aisle"] . "</td><td>";
			echo $row["rack"] . "</td><td>" . $row["shelf"] . "</td><td>" . $row["cName"] . "</td>";
			echo "<td><input type='submit' name='DeleteItem'  class = 'btn_trash' value=' '></td>";
			echo "</form></tr>";
			
			// Close off the tab if we've hit our peak
			if ( ( $itemCounter >= $TabSize ) ) {
				$tabStarted = FALSE;
				echo "</table>";
			}
		}
		// Close off the table if we didn't finish the table in the last tab
		if ( ( $tabStarted ) ) {
			echo "</table>";
		}
		
		echo "<br>";
		
		// Allow the user to adjust the tab size
		echo "<form id='tabForm' action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
		echo "Tab Size: ";
		echo "<select name='tabSize' onchange='this.form.submit()' >";
		for ($i=1; $i <= 100; $i+=($i<5 ? 1 : ($i<50 ? 5 : 10))) {
			echo "<option " . ($_POST['tabSize']!==NULL ? ($_POST['tabSize']==$i ? "selected" : "") 
							: ($i == $TabSize) ? "selected" : "") . " value='" . $i . "'>" . $i . "</option>";
		}
		echo "</select>";
		echo "</form>";
	} 
	else {
		echo "No items in database.";
	}
	
	closeDB($conn);

 ?>
 
	<script>
		// Open the default tab (if tabs exist)
		document.getElementById("defaultOpen").click();
	</script>