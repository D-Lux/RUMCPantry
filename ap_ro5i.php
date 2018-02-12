<?php
  $pageRestriction == 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
  <h3>Reallocation Items (inactive)</h3>	
	
  <div class="body-content">
	
	<?php
		// Set up server connection
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create our query string
		$sql = "SELECT itemName, itemID
				FROM Item
				JOIN Category
				ON Item.categoryID=Category.categoryID
				WHERE Category.name='REDISTRIBUTION'
				AND Item.isDeleted=1";
		$result = queryDB($conn, $sql);
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
			echo "<table> <tr> <th></th>";
			echo "<th>Item</th><th></th></tr>";
			
			while($row = sqlFetch($result)) {
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

				// Set Active button
				?>
				<td><input type="submit" class="btn-reactivate" name="activateRedistItem" value=" "
				onclick="return confirm('Do you want to reactivate this item?')"></td>
				<?php
				// Close off the row and form
				echo "</form>";
			}
			// Close off the table
			echo "</table><br>";
		} 
		else {
			echo "No redistribution items in database.";
		}
		
		closeDB($conn);
	?>

	<!-- View Active Items -->
	<form action="ap_ro5.php" method="post">
		<input type="submit" name="ShowInactive" value="View Active Redistribution Items">
    </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  if (getCookie("redistToggled") != "") {
    window.alert("Redistribution item reactivated!");
    removeCookie("redistToggled");
  }
</script>