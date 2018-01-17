<?php
include 'php/header.php';
include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">

<h3>Update Item</h3>
<div class="body_content">
<?php
    $itemID 		= $_GET['itemID'];
    $itemName 		="";
    $displayName	="";
    $price			= 0;
    $small			= 0;
    $medium			= 0;
    $large			= 0;
    $aisle			= 0;
    $rack			= 0;
    $shelf			= 0;
    $categoryID     = 0;
    $categoryName   = "";

     /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT isDeleted, itemID, itemName, displayName, price, small, medium, large, categoryID, aisle, rack, shelf FROM item WHERE itemID =". $_GET['itemID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
		$row = sqlFetch($result);
		if($row["isDeleted"] == false  )  {
			$itemID      = $row["itemID"];
			$itemName    = $row["itemName"];
			$displayName = $row["displayName"];
			$price       = $row["price"];
			$small 	     = $row["small"];
			$medium      = $row["medium"];
			$large       = $row["large"];
			$aisle       = $row["aisle"];;
			$rack        = $row["rack"];;
			$shelf       = $row["shelf"];;

		   
			$categoryID = $row["categoryID"];            
					
			$sql = "SELECT DISTINCT name, categoryID FROM Category WHERE categoryID = '$categoryID'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$categoryName = $row["name"];
			} 

		}
		else  {
		  echoDivWithColor("<h1><b><i>This item is deactivated.<br>Please reactivate it to edit it</i></b></h1>","purple");
		}
    }
    else {
        echoDivWithColor("<h1><b><i>Item does not exist!</h1></b></i>","red");
    }
	closeDB($conn);
	?>
	<form name="addItem" action="php/itemOps.php" onSubmit="return validateItemAdd()" method="post">
	<input type="hidden" name="itemID" value="<?= $itemID ?>">
	<div class="row">
		<div class="col-sm-4"><label class="required categoryField">Category: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i($categoryName,"categories","category","name","category", false);
			?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-4"><label class="required itemField">Item Name: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i($itemName,"itemNames","item","itemName","itemName", true);
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4"><label class="required displayField">Display Name: </label></div>
		<div class="col-sm-8">
			<?php
				createDatalist_i($displayName,"displayNames","item","displayName","displayName", true);
			?>
		</div>
	</div>
	<div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
		<div class="row">
			<div class="col-sm-2">Location:</div>
		</div>
		<div class="row">
			<!-- Aisle -->
			<div class="col-sm-1">Aisle:</div>
			<div class="col-sm-2">
				<select name="aisle">
					<option value=0>-</option>
					<?php
						for ($i = MIN_AISLE; $i <= MAX_AISLE; $i++) {
							echo "<option " . (($aisle==$i) ? 'selected' : '') . " value=" . $i . ">" . aisleDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
			<!-- Rack -->
			<div class="col-sm-1">Rack:</div>
			<div class="col-sm-2">
				<select name="rack">
					<option value=0>-</option>
					<?php
						for ($i = MIN_RACK; $i <= MAX_RACK; $i++) {
							echo "<option " . (($rack==$i) ? 'selected' : '') . " value=" . $i . ">" . rackDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
			<div class="col-sm-1">Shelf:</div>
			<div class="col-sm-2">
				<select name="shelf">
					<option value=0>-</option>
					<?php
						for ($i = MIN_SHELF; $i <= MAX_SHELF; $i++) { 
							echo "<option " . (($shelf==$i) ? 'selected' : '') . " value=" . $i . ">" . shelfDecoder($i) . "</option>";
						}
					?>
				</select>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-4">Price:</div>
		<div class="col-sm-8">
			<span>$</span>
			<input  style="width: 8em;" type="number" min="0" value="<?= $price ?>" step="0.01" name="price">
		</div>
	</div>
	<br>
 
	<!-- QTY to take -->
	<div style="border: 2px solid #499BD6; padding:5px;">
		<div class="row">
			<div class="col-sm-4">Order Form Quantities:</div>
		</div>
		<div class="row">
			<div class="col-sm-1">Small:</div>
			<div class="col-sm-2">
				<input type="number" min="0" max="20" value="<?= $small ?>" name="small" />
			</div>
			<div class="col-sm-1">Medium:</div>
			<div class="col-sm-2">
				<input type="number" min="0" max="20" value="<?= $medium ?>" name="medium" />
			</div>
			<div class="col-sm-1">Large:</div>
			<div class="col-sm-2">
				<input type="number" min="0" max="20" value="<?= $small ?>" name="large" />
			</div>
		</div>
	</div>
	<input type="submit" value="Update" name="updateItemIndividual">
</form>
 
<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>