<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>


	<?php
		echo "<h3>Order Form: ";
		$familyType = "";
		// Family Size 1-2 and Walk-Ins
		if (isset($_GET["1to2"])) { echo "One to Two / Walk-In"; $familyType = "small"; }
		// Family Size 3-4
		if (isset($_GET["3to4"])) { echo "Three to Four"; $familyType = "medium"; }
		// Family Size 5+
		if (isset($_GET["5Plus"])) { echo "Five+"; $familyType = "large"; }
		
		echo "</h3>";
		
		echo "<div id='errMsgs' style='display:none;color:red;'></div>";
    echo "<div id='msgLog' class='hoverSuccess hoverMsg' style='display:none;'></div>";
		if ($familyType === ""){
			header("location: /RUMCPantry/ap_oo1.php");
		}
		
		$familyToken = substr($familyType,0,1);
		
		echo "<div class='body-content'>";
		
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// * Query the database
		
		// Create our query from the Item and Category tables
		// Item Name, $familyType, category name, category family type, itemid
		$sql = "SELECT itemID, rack, shelf, aisle, itemName, Item." . $familyType . " as IQty, 
            Category.name as CName, Category." . $familyType . " as CQty, Item.categoryID			
            FROM Item
            LEFT JOIN Category
            ON Item.categoryID=Category.categoryID
            WHERE item.isDeleted=0
            AND Category.isDeleted=0
            AND Category.name<>'Specials'
            AND Category.name<>'redistribution'
            ORDER BY Category.name, aisle, rack, shelf, itemName";

		$items = returnAssocArray(queryDB($conn, $sql));
		
		if ( count($items) <= 0 ) {
			echo "No Items were in the database.";
      die();
		}
    
    $categoryList = [];
    foreach ($items as $item) {
      if (! in_array($item['CName'], $categoryList)) {
        $categoryList[$item['categoryID']] = $item['CName'];
      }
    }
    
		// *********************************************
		// * Show our Dropdown
    $firstCat = null;
    echo "<select id='catSelector'>";
    foreach ($categoryList as $cid => $category) {
			echo "<option value='itemList" . $cid . "'>" . $category . "</option>";
      if ($firstCat == null ) {
        $firstCat = '#itemList' . $cid;
      }
    }
    echo "</select>";
    
		
		// ******************************************************
		// ** Create our table of information

		$currCategory = "";
		//while ($item = sqlFetch($itemList)) {
    foreach ($items as $item) {
			// if this is a new category
			if ($currCategory != $item['CName']) {
				// If we were in a different category, close off that category's table
				if ($currCategory != "") {
					echo "</table>";
					echo "</div>";
				}
				// Print out the category name, followed by the selection qty and start a new table
				echo "<div id='itemList" . $item['categoryID'] . "' class='tabcontent' style='display:none;text-align:center;'>";
				echo "Selection Quantity: ";
					// Text field version
				echo "<input id='cid" . $familyToken . $item['categoryID'] . "' type='text' maxlength=2 class='CQty input-number'
						value=" . $item['CQty'] . " onchange='AJAX_UpdateCQty(this)' style='width:40px;'><br>";
				
				//echo "<select class='CQty' id='cid" . $familyToken . $item['categoryID'] . 
				//		"' onchange='AJAX_UpdateCQty(this)'>";
				//for ($i = 0; $i < 11; $i++) {
				//	echo "<option value=$i " . ($i == $item['CQty'] ? "Selected" : "") . ">$i</option>";
				//}
				//echo "</select><br><br>";
				
				$currCategory = $item['CName'];
				// Table headers
				echo "<table class='table'><tr><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Qty</th></tr>";
			}
			// Print this item's name and show it's location
			echo "<tr><td>" . $item['itemName'] . "</td>";
			echo "<td>" . aisleDecoder($item['aisle']) . "</td>";
			echo "<td>" . rackDecoder($item['rack']) . "</td>";
			echo "<td>" . shelfDecoder($item['shelf']) . "</td>";
			
			// *****************************************
			// This is the dropdown selection list
			//echo "<td><select class='IQty' id='iqty" . $familyToken . $item['itemID'] . 
			//		"' onchange='AJAX_UpdateQty(this)'>";
			
			//for ($i = 0; $i < 11; $i++) {
			//	echo "<option value=$i " . ($i == $item['IQty'] ? "Selected" : "") . ">$i</option>";
			//}
			//echo "</select></td>";
			
				// Text field version
			echo "<td><input id='iqty" . $familyToken . $item['itemID'] . "' type='text' maxlength=2  class='IQty input-number' 
						value=" . $item['IQty'] . " onchange='AJAX_UpdateQty(this)' style='width:40px;'></td>";
			
		}
		echo "</table>";
		echo "</div>";
	?>
	
  
<?php include 'php/footer.php'; ?>
<script src="js/orderFormOps.js"></script>
<script>
  // Open the default tab (if tabs exist)
  //document.getElementById("defaultOpen").click();
  $( document ).ready(function() {
    $("select").on("change", function() {
      $("#errMsgs").hide(200);
    });
    $(".tablinks").on("click", function() {
      $("#errMsgs").hide(200);
    });
    $("<?=$firstCat?>").css({ "display": "block"});
    $("#catSelector").on("change", function(e) {
      var showTab = "#" + $(this).val();
      $(".tabcontent").css({ "display": "none"});
      $(showTab).css({ "display": "block"});
    });
  });
</script>
