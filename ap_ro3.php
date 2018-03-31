<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
	<h3>Add New Reallocation Partner</h3>

	<div class="body-content">

		<form id="createRedistPartner">
			<input type="hidden" value=1 name="submitNewRedistPartner">
			<div class="row">
				<div class="col-sm-3">Partner Name:</div>
				<div class="col-sm-4"><input type="text" name="partnerName" maxlength="45"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">Email:</div>
				<div class="col-sm-4"><input type="email" name="email"></div>
			</div>

			<div class="row">
				<div class="col-sm-3">Phone Number:</div>
				<div class="col-sm-8">(<input class="input-phone input-number" maxlength="3" type="text" name="phone1">)
				<input class="input-phone input-number" maxlength="3" type="text" name="phone2">-
				<input class="input-phone input-number" maxlength="4" type="text" name="phone3"></div>
			</div>

			<div class="row">
				<div class="col-sm-3">Street Address:</div>
				<div class="col-sm-4"><input type="text" name="addressStreet"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">City:</div>
				<div class="col-sm-4"><input type="text" name="addressCity"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">Zip Code:</div>
				<div class="col-sm-4"><input class="input-number"  type="text" maxlength="5" name="addressZip"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">State:</div>
				<div class="col-sm-4">
					<select name="addressState">
						<?php getStateOptions(); ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="msg-warning" id="warningMsgs"></div>
	    <input type="submit" class="btn-nav" value="Create" >
	  </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
	$("select").chosen();
	// For adding a new donation partner
	$(".btn-nav").on("click", function(e) {
	  e.preventDefault();
	  $("#warningMsgs").stop(true, true).hide();
	  var fieldData = $("#createRedistPartner").serialize().trim();
	  $.ajax({
	    url: "php/redistOps.php",
	    data: fieldData,
	    type: "POST",
	    dataType: "json",
	    context: document.body,
	    success: function(msg) {
	      if (msg.error == '') {
	        setCookie("newPartner", 1, 30);
	        window.location.assign(basePath + "ap_ro4.php?id=" + msg.id);
	      }
	      else {
	        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
	      }
	    },
		});
	});
</script>