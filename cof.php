<?php include 'php/utilities.php'; ?>
<?php include 'php/beanOps.php'; ?>
<script src='js/orderFormOps.js'></script>
		<button id='btn_back' onclick="goBack()">Back</button>
		<h3>Order Form</h3>
		<div class="body_content">
			<?php
				// *******************************************************
				// * Run our SQL Queries
				// * 1.) Family size information
				// * 2.) Order form database
				
				// -= Family size query =-
				$famSql = "SELECT (numOfKids + numOfAdults) as familySize 
						   FROM client
						   WHERE clientID=" . $_POST['clientID'];
				$walkinSql = "SELECT walkIn
						   FROM Invoice
						   WHERE invoiceID=" . $_POST['invoiceID'];
				
				//Run this query so we know what to grab from the item database
				$conn = createPantryDatabaseConnection();
				if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
				
				// Default to walkIn
				$familyType = "Small";
				
				$famQuery = queryDB($conn, $famSql);
				if ($famQuery === FALSE) {
					echo "sql error: " . mysqli_error($conn);
					echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
				}
				else {
					$famData = sqlFetch($famQuery);
					$familyType = familySizeDecoder($famData['familySize']);
				}
				
				// Force family type to small if this is a walkin client
				$WIQuery = queryDB($conn, $walkinSql);
				$walkIn = 0;
				if ($WIQuery === FALSE) {
					echo "sql error: " . mysqli_error($conn);
					echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
				}
				else {
					$walkInData = sqlFetch($WIQuery);
					$walkIn = $walkInData['walkIn'];
				}
				if ($walkIn == 1) {
					$familyType = "Small";
				}
				
				// --== Item databse query ==--
				$sql = "SELECT itemID, displayName, itemName, Item." . $familyType . " as IQty, Category.name as CName, 
						Category." . $familyType . " as CQty, Item.categoryID as CID
						FROM Item
						JOIN Category
						ON Item.categoryID=Category.categoryID
						WHERE Item.isDeleted=0
						AND Category.isDeleted=0
						AND Category.name<>'Specials'
						AND Category.name<>'redistribution'
						AND Item." . $familyType . ">0
						AND Category." . $familyType . ">0 
						ORDER BY Category.name, Item.displayName";

				$itemList = queryDB($conn, $sql);
				
				if ($itemList === FALSE) {
					echo "sql error: " . mysqli_error($conn);
					echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
				}
				closeDB($conn);
				
				// ************************************************************
				// * Create the order form
				
				// Start the form and add a hidden field for client info
				echo "<form method='post' action='php/orderOps.php' name='CreateInvoiceDescriptions'>";
				echo "<input type='hidden' value=" . $_POST['clientID'] . " name='clientID'>";
				echo "<input type='hidden' value=" . $_POST['invoiceID'] . " name='invoiceID'>";
				echo "<input type='hidden' value=" . $walkIn . " name='walkInStatus'>";
				
				// Set defaults
				$currCategory = "";
				
				// *************************************************
				// * Normal Items
				
				// Special case ID holders for beans (bagged and canned)
				// We have to pull them apart, because they may not be in order
				$CanBeans = array();
				$BagBeans = array();
				
				$BeanQty = 0;
				
				// Roll through the items and create the order form
				while ($item = sqlFetch($itemList)) {
					// Check skipped categories for walkIn 
					if ( showCategory($walkIn, $item['CName']) ){
						
						// Special case for beans (store off so we can order them)
						if ($item['CName'] == "Beans") {
							if ( $BeanQty == 0 ) { 
								$BeanQty = $item['CQty'];
							}
							$currCategory = $item['CName'];
							
							// Canned
							if ( strpos($item['itemName'], "Bag") === FALSE ) {
								if (!ISSET($CanBeans[$item['itemID']])) {
									$CanBeans[$item['itemID']] = new CLASS_BeanInfo($item['displayName'], $item['IQty']);
								}
							}
							// Bagged
							else {
								if (!ISSET($BagBeans[$item['itemID']])) {
									$BagBeans[$item['itemID']] = new CLASS_BeanInfo($item['displayName'], $item['IQty']);
								}
							}
						}
						else {
							// If we've just looked at the beans, we should spit them all out before continuing
							if ($currCategory == "Beans") {
								showBeanCategory($CanBeans, $BagBeans, $BeanQty);
							}
							if ($currCategory != $item['CName']) {
								echo "<h4>" . $item['CName'] . "</h4>";
								// Create a special div so the client can see an updated count of selected items
								echo "<h5><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] . 
									" (" . ($item['CQty']) . " remaining)</div></h5>";
								// Include hidden values so we can track the category
								echo "<input type='hidden' value=" . $item['CQty'] . " id=" . $item['CName'] . ">";
								$currCategory = $item['CName'];
							}
							// Display the Item name
							echo $item['displayName'];
							
							// Show the selection boxes
							echo "<div class='selectionBoxes'>";
							for ($i = 0; $i < $item['IQty']; $i++) {
								// Value is the item's ID
								// Name is the item's category[] (in array)
								$customID = "box" . $item['itemID'] . "n" . $i;
								echo "<input type='checkbox' id=$customID value=" . $item['itemID'] . 
										" onclick='countOrder(this)' name='" . $item['CName'] . "[]'>";
								echo "<label for=$customID ></label>";
							}
							echo "</div>";
							echo "<br>";
						}
					}
					
				}
				// If our last category was beans, we gotta spit it out here
				if ($currCategory == "Beans") {
					showBeanCategory($CanBeans, $BagBeans, $BeanQTY);
				}
				
				// *************************************************
				// * Specials
				
				if (!$walkIn) {;
					$specialsFile = fopen("specials.txt","r") or die();
					echo "<hr><h2>Specials</h2><h3>Please select one item from each section</h3><hr>";
					$conn = createPantryDatabaseConnection();
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
											FROM Item
											WHERE itemID='" . $itemLine[$i] . "'
											LIMIT 1";
									$itemQuery = queryDB($conn, $sql);
									
									if ($itemQuery == NULL || $itemQuery->num_rows <= 0){
										echo "sql error: " . mysqli_error($conn);	
									}
									else {
										$itemInfo = sqlFetch($itemQuery);
										$customID = "box" . $itemLine[$i] . "s" . $i;
										echo "<input type='radio' id=$customID value=" . $itemLine[$i] . "
												name='savedItem" . $specialItemNum . "'>" . $itemInfo['displayName'];
												
										echo "<label for=$customID ></label>";
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
			<br>
				<button type="submit" name="CreateInvoiceDescriptions">Submit Order</button>
			</form>
		</div><!-- /body_content -->
	</div><!-- /content -->
	</body>
</html>