<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 10;
  include 'php/checkLogin.php';
  include 'php/orderFormMessageBox.php';
  include 'php/header.php';
  include 'php/beanOps.php';
  include 'php/backButton.php';
?>

<?php
	// Open the database connection
  $conn = connectDB();
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }


  $invoiceID  = 0;
  if (isset($_GET['invoiceID'])) {
    $invoiceID =  $_GET['invoiceID'];
  }
  else {
    redirectPage("ap_oo3.php");
  }

  // *******************************************************
	// * Query for starting information
  // Using our invoice ID, get the family size, client ID and name
  $sql = " SELECT client.clientID, (numOfAdults + numOfKids) as familySize,
                  walkIn, CONCAT(lastName , ', ', firstName) as cName
            FROM invoice
            JOIN client
              ON client.clientID = invoice.clientID
            JOIN familymember
              ON familymember.clientID = invoice.clientID
            WHERE invoiceID = " . $invoiceID;

  $result = runQueryForOne($conn, $sql);

  $clientID   = $result['clientID'];
  $familySize = $result['familySize'];
  $walkIn     = $result['walkIn'];

  $familyType = ($walkIn == 1) ? "Small" : familySizeDecoder($familySize);

  echo "<h3>Review Order Form</h3>";
  echo "<h4>Client: " . $result['cName'] . "</h4>";
	echo "<div class='body-content'>";
	// ************************************
	// --== Client current order query ==--
	$orderSql = "SELECT itemID, quantity, special
				 FROM invoicedescription
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
	$sql = "SELECT itemID, itemName, displayName, item." . $familyType . " as IQty, category.name as CName,
			category." . $familyType . " as CQty, item.categoryID as CID
			FROM item
			JOIN category
			ON item.categoryID=category.categoryID
			WHERE item.isDeleted=0
			AND category.isDeleted=0
			AND category.name<>'Specials'
			AND category.name<>'redistribution'
			AND item." . $familyType . ">0
			AND category." . $familyType . ">0
			ORDER BY category.formOrder, item.displayName";

	$itemList = queryDB($conn, $sql);

	if ($itemList === FALSE) {
		echo "sql error: " . mysqli_error($conn);
		echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
	}

	closeDB($conn);

	// ************************************************************
	// * Create the order form

	// Start the form and add a hidden field for client info
	echo "<form method='post' id='iReviewOrderForm' action='php/orderOps.php' name='CreateReviewedInvoiceDescriptions'>";
  echo "<input type='hidden' value=1 name='CreateReviewedInvoiceDescriptions'>";
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

					echo "<h4 class='text-center'>" . $item['CName'] . "</h3>";
					// Create a special div so the client can see an updated count of selected items
					echo "<h5 class='text-center'><div id='Count" . $item['CName'] . "'>You may select up to " . $item['CQty'] .
						" (" . ($item['CQty']) . " remaining)</div></h4>";
					// Include hidden values so we can track the category
					echo "<input type='hidden' value=" . $item['CQty'] . " id='" . $item['CName'] . "'>";
					$currCategory = $item['CName'];
				}


        // Display the Item name
        echo "<div class='row'>";
        echo "<div class='col-sm text-right' style='margin:auto;'>";
        echo $item['displayName'];
        echo "</div>";
				echo "<div class='selectionBoxes col-sm'>";
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
				echo "</div>";
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
		$conn = connectDB();
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


	}
?>
      <div class="text-center" id="iSubmitError"></div>
      <div class="text-center">
				<button type="submit" class='btn-nav' id="iSubmitReview" name="CreateReviewedInvoiceDescriptions">Verify Order</button>
			</div>
		</form>

<?php include 'php/footer.php'; ?>
<script src='js/orderFormOps.js'></script>

<script type='text/javascript'>
  updateCheckedQuantities();
  
  $("#iReviewOrderForm").on("submit", function(e) {
    $("#iSubmitReview").prop("disabled", true);
    e.preventDefault();
    $.ajax({
      url: "php/orderOps.php",
      dataType: "html",
      data: $("#iReviewOrderForm").serialize(),
      method: "POST",
      success: function(response) {
        if (response == "") {
          goBack();
        }
        else {
          $("#iSubmitError").html("<strong><p style='color:red;font-size:1.4em;'>" + response + "</p></strong>");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("#iSubmitError").html("<strong><p style='color:red;font-size:1.4em;'>There was an error connecting to the network. " +
          "Please wait a moment and try again. If the problem persists, please check the network connection.</p></strong>");
      },
      complete: function() {
        $("#iSubmitReview").prop("disabled", false);
      },
    });
  });
</script>

