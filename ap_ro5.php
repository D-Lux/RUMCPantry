<!doctype html>
<html>

<head>
    <script src="js/utilities.js"></script>
	<?php include 'php/utilities.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/toolTip.css" />
	<?php include 'php/checkLogin.php';?>

    <title>Redistribution Items</title>
</head>

<body>
	<button onclick="goBack()">Go Back</button>
    <h1>
        Redistribution Items
    </h1>

	<script>
		if (getCookie("redistItemUpdated") != "") {
			window.alert("Redistribution item updated!");
			removeCookie("redistItemUpdated");
		}
		if (getCookie("newRedistItem") != "") {
			window.alert("New Item Added!");
			removeCookie("newRedistItem");
		}
		if (getCookie("redistToggled") != "") {
			window.alert("Redistribution item deactivated!");
			removeCookie("redistToggled");
		}
	</script>
	
	
	
	<?php
		// Set up server connection
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query string
		$sql = "SELECT itemName, itemID, price, Item.small as weight
				FROM Item
				JOIN Category
				ON Item.categoryID=Category.categoryID
				WHERE Category.name='REDISTRIBUTION'
				AND Item.isDeleted=0";
		$items = queryDB($conn, $sql);
		
		// loop through the query results
		if ($items!=null && $items->num_rows > 0) {
			echo "<table> <tr> <th></th>";
			echo "<th>Item</th><th>Price</th><th>Weight</th><th></th></tr>";
			
			while($row = sqlFetch($items)) {
				// Start Table row
				echo "<tr>";
				
				// Start the form for this row (so buttons act correctly based on client)
				echo "<form action='php/redistOps.php' method='post'>";
				
				// Get the client ID so we can properly do the update and delete operations
				$id = $row["itemID"];
				echo "<input type='hidden' name='id' value='$id'>";

				// Update button				
				echo "<td><input type='submit' name='updateRedistItem' value='Update'></td>";
				
				// Information Fields
				echo "<td>" . $row['itemName'] . "</td>";
				echo "<td>" . $row['price'] . "</td>";
				echo "<td>" . $row['weight'] . "</td>";
				
				// Set inactive button
				?>
				<td><input type="submit" class="btn_trash" name="deleteRedistItem" value=" "
				onclick="return confirm('Are you sure you want to deactivate this item?')"></td>
				<?php
				// Close off the row and form
				echo "</form>";
			}
			// Close off the table
			echo "</table><br>";
		} 
		else {
			echo "No items in the redistribution category.";
		}
		
		$conn->close();
	?>
	<br><br>
	
	<!-- NEW Redistribution Item -->
	<form action="php/redistOps.php" method="post">
		<input type="submit" name="newRedistItem" value="New Redistribution Item">
    </form>
	
	<br>
	<!-- View Deactivated Items -->
	<form action="ap_ro5i.php" method="post">
		<input type="submit" name="ShowInactive" value="View Deactivated Items">
    </form>

	<div id="errorLog"></div>
</body>
</html>