<?php 
  include 'php/header.php';
  include 'php/beanOps.php';
  include 'php/backButton.php';
?>

<?php
	// Open the database connection
  $conn = createPantryDatabaseConnection();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
  
  
  $invoiceID  = 0;
  if (isset($_GET['invoiceID'])) {
    $invoiceID =  $_GET['invoiceID'];
  }
  else {
    header("Location: /RUMCPantry/ap_oo3.php"); // TODO
  } 
  
  // *******************************************************
	// * Query for starting information
  // Using our invoice ID, get the family size, client ID and name
  $sql = " SELECT Client.clientID, (numOfAdults + numOfKids) as familySize, 
                  walkIn, CONCAT(lastName , ', ', firstName) as cName
            FROM Invoice
            JOIN Client
              ON Client.clientID = Invoice.clientID
            JOIN FamilyMember
              ON FamilyMember.clientID = Invoice.clientID
            WHERE invoiceID = " . $invoiceID;
            
  $result = runQueryForOne($conn, $sql);  
  
  $clientID   = $result['clientID'];
  $familySize = $result['familySize'];
  $walkIn     = $result['walkIn'];
  
  $familyType = ($walkIn == 1) ? "Small" : familySizeDecoder($familySize);
  
  echo "<h3>Review Order Form</h3>";
  echo "<h4>Client: " . $result['cName'] . "</h4>";
	echo "<div class='body_content'>";
	// ************************************
	// --== Client current order query ==--
	$orderSql = "SELECT itemID, quantity, special
				 FROM InvoiceDescription
				 WHERE invoiceID=" . $invoiceID;
	$orderData = queryDB($conn, $orderSql);
	if ($orderData === FALSE) { die("Order could not be found or has not yet been completed"); }
	
	$clientOrder = FALSE;
	$clientSpecials = FALSE;
	while ($desc = sqlFetch($orderData)) {
		// if this item is a special, save it as a special
		// To avoid issues where items appear on both the order form and the specials list
		if ($desc['special']) {
			$clientSpecials[$desc['itemID']] = $desc['quantity'];
		}
		else {
			$clientOrder[$desc['itemID']] = $desc['quantity'];
		}
	}

	if (!$clientOrder) { die("Order could not be found or has not yet been completed"); }
	
	// *****************************
	// --== Item database query ==--
	$sql = "SELECT itemID, itemName, displayName, Item." . $familyType . " as IQty, Category.name as CName, 
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
	echo "<form method='post' action='php/orderOps.php' name='CreateReviewedInvoiceDescriptions'>";
	echo "<input type='hidden' value=" . $clientID . " name='clientID'>";
	echo "<input type='hidden' value=" . $invoiceID . " name='invoiceID'>";
	echo "<input type='hidden' value=" . $walkIn . " name='walkInStatus'>";
	
	// Set defaults
	$currCategory = "";
	$divOpen = false;
	
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
				if ($divOpen) {
					echo "</div>"; // closing orderSection div from previous loop
					$divOpen = false;
				}
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
					showBeanCategory($CanBeans, $BagBeans, $BeanQty, $clientOrder);
				}
				if ($currCategory != $item['CName']) {
					if ($divOpen) {
						echo "</div>"; // closing orderSection div from previous loop
					}
					echo "<div class='orderSection'>";
					$divOpen = true;
					
					echo "<h3>" . $item['CName'] . "</h3>";
					// Create a special div so the client can see an updated count of selected items
					echo "<h4><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] . 
						" (" . ($item['CQty']) . " remaining)</div></h4>";
					// Include hidden values so we can track the category
					echo "<input type='hidden' value=" . $item['CQty'] . " id='" . $item['CName'] . "'>";
					$currCategory = $item['CName'];
				}
		

				// Display the Item name
				echo $item['displayName'];
				echo "<div class='selectionBoxes'>";
				for ($i = 0; $i < $item['IQty']; $i++) {
					// Value is the item's ID
					// Name is the item's category[] (in array)
					$customID = "box" . $item['itemID'] . "n" . $i;
					echo "<input type='checkbox' id=$customID value=" . $item['itemID'];
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
					echo "<label for=$customID ></label>";
				}
				echo "</div>";
				echo "<br>";
			}
		}
	}
	if ($divOpen) {
		echo "</div>"; // closing final orderSection div
		$divOpen = false;
	}
	// If our last category was beans, we gotta spit it out here
	if ($currCategory == "Beans") {
		showBeanCategory($CanBeans, $BagBeans, $BeanQty, $clientOrder);
	}
	
	// *************************************************
	// * Specials
	
	if (!$walkIn) {
		echo "<div id='specialsSection'>";
		$specialsFile = fopen("specials.txt","r") or die();
		echo "<hr><h2>Specials</h2><h3>Please select one from each section</h3>";
		$conn = createPantryDatabaseConnection();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$specialItemNum = 1;
		while(!feof($specialsFile)) {
			$itemLine = explode(",", fgets($specialsFile));
			if (sizeof($itemLine) > 1) {
				
				// Handle the div for the border box
				if ($divOpen) {
					echo "</div>"; // closing orderSection div from previous loop
				}
				echo "<div class='orderSection'>";
				$divOpen = true;
				
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
							echo "<input type='radio' id=$customID value=" . $itemLine[$i];
							echo " name='savedItem" . $specialItemNum . "' ";
							
							// Check if the item was selected
							if ( ISSET($clientSpecials[$itemLine[$i]]) ) {
								if ($clientSpecials[$itemLine[$i]] > 0) {
									$clientSpecials[$itemLine[$i]]--;
									echo " checked ";
								}
							}
							echo " >" . $itemInfo['displayName'];
							echo "<label for=$customID ></label>";
							echo "<br>";
						}
					}
				}
				$specialItemNum++;
			}
		}
		closeDB($conn);
		if ($divOpen) {
			echo "</div>"; // closing orderSection div from previous loop
			$divOpen = false;
		}
		echo "</div>"; // Closing specials div
					
		// ***********************************
		// * Run a javascript function to show specials if there are any
		if ($specialItemNum > 1) {
			echo "<script type='text/javascript'> showSpecials(); </script>";
		}
	}
	
	// ***********************************
	// * Run a javascript function to update selection quantity strings
	echo "<script type='text/javascript'> updateCheckedQuantities(); </script>";
	
?>
			<button type="submit" name="CreateReviewedInvoiceDescriptions">Verify Order</button>
		</form>
<?php include 'php/footer.php'; ?>
<script src='js/orderFormOps.js'></script>
