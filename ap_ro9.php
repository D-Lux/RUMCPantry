<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

<style>
#newItems .chosen-container {
  width:300px !important;
  padding-top:10px !important;
}
</style>

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
		$sql = "SELECT c.clientID as ID, fm.lastName as partner
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
		<div id="addRedistItemTemplate" style="display:none;border: 1px solid black;border-radius:4px;margin-top: 5px;" >
      <div class="row">
        <div class="col-sm-1">
          <button class="btn-icon btn-remove-item"><i style="padding-left:10px;" class="fa fa-trash"></i></button>
        </div>
        <div class="col-sm-6">
          <select class="clonedChosen" name="items[]" style="margin-top:8px;">
            <option value=0>Select an item...</option>
            <?php foreach ($items as $item) { ?>
              <option value=<?=$item['itemID']?>><?=$item['itemName']?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-3">
          <input type="text" style="width:3em;margin-top:8px;" placeholder="Qty" class="input-number" name="qty[]" maxlength=3>
        </div>
      </div>
    </div>

    <!-- Form for reallocation -->
		<form id="reallocationForm">
      <input type="hidden" value=1 name="submitRedistribution">
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
        <input type="button" value="Add Item" class="btn-nav btn-nav-sm" id="addItemBtn">
      </div>
      <div class="clearfix"></div>
			<div id="newItems"></div>

			<!-- Save button and close the form -->
			<input type="submit" class="btn-nav" value="Create Reallocation">
    </form>
    <div id="errorLog"></div>

<?php include 'php/footer.php'; ?>
<script src="js/redistOps.js"></script>
<script type="text/javascript">
  $("#partnerSelect").chosen();

  var itemID = 0;
  $("#addItemBtn").click(function(e){
    e.preventDefault();

    // Clone the item and save a reference to it
    var newItem = $("#addRedistItemTemplate").clone();
    // Append it and show it to the items section
    $(newItem).appendTo("#newItems").show(300);
    // Set it's ID to something unique so we can find it later
    $(newItem).attr("id", "item" + itemID);
    // Activate the select box
    $("#item" + itemID + " select").chosen();

    itemID++;
  });

  $("form").on("click", ".btn-remove-item", function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to remove this item?")) {
      $(this).parent().parent().parent().remove();
    }
  });

  // For adding a new donation partner
  $("input[type='submit']").on("click", function(e) {
    e.preventDefault();
    $("#errorLog").stop(true, true).hide();
    var fieldData = $("#reallocationForm").serialize();
    $.ajax({
      url: "php/redistOps.php",
      data: fieldData,
      type: "POST",
      dataType: "json",
      context: document.body,
      success: function(msg) {
        if (msg.error == '') {
          setCookie("newRedistribution", 1, 30);
          window.location.assign(basePath + "ap_ro8.php");
        }
        else {
          $("#errorLog").html("<pre>" + msg.error + "</pre>").show(300);
        }
      },
    });
  });

  //onSubmit="return validateRedistribution()"
  // Handle form submit
</script>