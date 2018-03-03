<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>

	<div class="body-content">

	<form id="submitNewRedistItem" >
    <input type="hidden" name="submitNewRedistItem">
		<div class="row">
      <div class="col-sm-3">Item Name:</div>
      <div class="col-sm-4"><input type="text" name="itemName" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-3">Price:</div>
      <div class="col-sm-4"><input class="input-number-price" type="text" name="price" max=5></div>
    </div>
			<div id="itemName" class="required"><label for="itemNameField"></label>
				</div>
			<label for="priceInput"></label><br>
			<label for="weightInput">Weight:</label><input id="weightInput" type="number" name="weight" min=0 step=".01"><br>
		</div><br>
        <input type="submit" name="submitNewRedistItem" value="Create Item" >
    </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript>">

  $("input").on("Change", function(e) {
    alert("test");
  // Allow for tab/backspace/delete
    //alert(key.keyCode);
  });

  // $(".btn-nav").on("click", function(e) {
  //   e.preventDefault();
  //   $("#warningMsgs").stop(true, true).hide();
  //   var fieldData = $("#submitNewRedistItem").serialize().trim();
  //   $.ajax({
  //     url: "php/redistOps.php",
  //     data: fieldData,
  //     type: "POST",
  //     dataType: "json",
  //     context: document.body,
  //     success: function(msg) {
  //       if (msg.error == '') {
  //         setCookie("newPartner", 1, 30);
  //         window.location.assign("/RUMCPantry/ap_ro4.php?id=" + msg.id);
  //       }
  //       else {
  //         $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
  //       }
  //     },
  //   });
  // });
</script>