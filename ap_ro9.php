<?php
$pageRestriction = 99;
include 'php/header.php';
include 'php/backButton.php'
?>


  <h3>New Reallocation</h3>

	<div class="body-content">

	<?php
		// Set up server connection
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// ***************************************
		// * Partner data
		$sql = "SELECT c.clientID as ID, CONCAT(fm.lastName, ' - ', city) as partner
            FROM client c
            JOIN familymember fm
            ON c.clientID=fm.clientID
            WHERE c.redistribution=1
            AND c.isDeleted=0";

		$partners = runQuery($conn, $sql);

		if (!is_array($partners)) {
      die("No reallocation partners found.");
		}

		// ************************
		// * Item Info
		$sql = "SELECT itemID, itemName
            FROM item
            JOIN category
              ON category.categoryID = item.categoryID
            WHERE category.name='REDISTRIBUTION'
            AND item.isDeleted=0";

		$items = runQuery($conn, $sql);

		if (!is_array($items)) {
			die("No redistribution items available.");
		}

		// Close off the database connection because we no longer need it
		closeDB($conn);
    ?>
    
    <!-- Secret div to fight inflation -->
		<div id="addRedistItemTemplate" style="display:none;border: 1px solid black;border-radius:2px;" >
      <div class="row">
        <div class="col-sm-1">
          <button class="btn-icon btn-remove-item"><i class="fa fa-trash"></i></button>
        </div>
        <div class="col-sm-6">
          <select id="items[]">
            <option value=0>Select an item...</option>
            <?php foreach ($items as $item) { ?>
              <option value=<?=$item['itemID']?>><?=$item['itemName']?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-3">
          <input type="text" style="width:3em;" placeholder="Qty" class="input-text" name="qty[]" maxlength=3>
        </div>
      </div>
    </div>

    <!-- Form for reallocation -->
		<form method="post" onSubmit="return validateRedistribution()" action="php/redistOps.php">
      <div class="row">
        <div class="col-sm-3">Date:</div>
        <div class="col-sm-8"><input type="date" value="<?=date('Y-m-d')?>" name="date"></div>
      </div>
      <div class="row">
        <div class="col-sm-3">Parnter</div>
        <div class="col-sm-8">
          <select name="partnerID" id="partnerSelect">
            <option value=0>Select a Partner</option>
            <?php foreach ($partners as $partner) { ?>
              <option value=<?=$partner['ID']?>><?=$partner['partner']?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="row">
        <!-- Add new item button -->
        <input type="button" value="Add Item" class="btn-table" id="addItemBtn">
      </div>
      <div class="clearfix"></div>
			<div id="newItems"></div>

			<!-- Save button and close the form -->
			<input type="submit" class="btn-nav" name="submitRedistribution" value="Save">
    </form>

<?php include 'php/footer.php'; ?>
<script src="js/redistOps.js"></script>
<script type="text/javascript">
  $("select").chosen();
  
  $("#addItemBtn").click(function(e){
    e.preventDefault();
    $("#addRedistItemTemplate").clone().appendTo("#newItems").show(300);
    //$("select").chosen();
  });
  
  // Handle delete item buttons
  $("form").on("click", ".btn-remove-item", function(e) {
    e.preventDefault();
    //deleteRedistItem(this)
  });
</script>