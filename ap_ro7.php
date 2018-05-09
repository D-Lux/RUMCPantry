<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';

  $conn = connectDB();
  $sql = "SELECT itemName, price, aisle as weight
          FROM item
          WHERE itemID=" . $_GET['id'];
  $itemInfo = runQueryForOne($conn, $sql);
  closeDB($conn);
  if ($itemInfo === FALSE) {
		die("Unable to find item");
	}
?>
	<h3>Update Reallocation Item</h3>

	<div class="body-content">
    <div id="redistSuccess" class="hoverMsg hoverSuccess" style="display:none;"></div>
		<form id="submitUpdateRedistItem" >
      <input type="hidden" name="submitUpdateRedistItem" value=<?=$_GET['id']?>>
      <div class="row">
        <div class="col-sm-3">Item Name:</div>
        <div class="col-sm-4"><input type="text" name="itemName" maxlength="45" value="<?=$itemInfo['itemName']?>"></div>
      </div>
      <div class="row">
        <div class="col-sm-3">Price:</div>
        <div class="col-sm-4"><input class="input-number" type="text" name="price" max=10 value="<?=$itemInfo['price']?>"></div>
      </div>
      <div class="row">
        <div class="col-sm-3">Weight:</div>
        <div class="col-sm-4"><input class="input-number" type="text" name="weight" max=10 value="<?=$itemInfo['weight']?>"></div>
      </div>
      <div class="clearfix"></div>
      <div class="msg-warning" id="warningMsgs"></div>
      <input type="submit" class="btn-nav" name="submitUpdateRedistItem" value="Update">
    </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  $(".btn-nav").on("click", function(e) {
    e.preventDefault();
    $("#warningMsgs").stop(true, true).hide();
    var fieldData = $("#submitUpdateRedistItem").serialize().trim();
    $.ajax({
      url: "php/redistOps.php",
      data: fieldData,
      type: "POST",
      dataType: "json",
      context: document.body,
      success: function(msg) {
        if (msg.error == '') {
          $("#redistSuccess").stop(true,true).hide().html("Update Successful!").show(250).delay(2000).hide(300);
        }
        else {
          $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
        }
      },
    });
  });
</script>