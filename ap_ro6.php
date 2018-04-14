<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
  <h3>New Reallocation Item</h3>
  
	<div class="body-content">

	<form id="submitNewRedistItem" >
    <input type="hidden" name="submitNewRedistItem" value=1>
		<div class="row">
      <div class="col-sm-3">Item Name:</div>
      <div class="col-sm-4"><input type="text" name="itemName" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-3">Price:</div>
      <div class="col-sm-4"><input class="input-number" type="text" name="price" max=10></div>
    </div>
    <div class="row">
      <div class="col-sm-3">Weight:</div>
      <div class="col-sm-4"><input class="input-number" type="text" name="weight" max=10></div>
    </div>
		<div class="clearfix"></div>
    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" name="submitNewRedistItem" value="Create Item" >
  </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  $(".btn-nav").on("click", function(e) {
    e.preventDefault();
    $("#warningMsgs").stop(true, true).hide();
    var fieldData = $("#submitNewRedistItem").serialize().trim();
    $.ajax({
      url: "php/redistOps.php",
      data: fieldData,
      type: "POST",
      dataType: "json",
      context: document.body,
      success: function(msg) {
        if (msg.error == '') {
          setCookie("newRedistItem", 1, 30);
          window.location.assign(basePath + "ap_ro5.php");
        }
        else {
          $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
        }
      },
    });
  });
</script>