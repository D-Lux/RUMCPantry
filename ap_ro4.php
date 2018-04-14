<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

  // Set up server connection
	$conn = connectDB();

	// *************************************************
	// Query the database

	$sql = "SELECT email, phoneNumber, address, city, state, zip, client.notes, familymember.lastName as name
					FROM client
					JOIN familymember
					ON familymember.clientID=client.clientID
					WHERE client.clientID=" . $_GET['id'];
	$partnerInfo = runQueryForOne($conn, $sql);

	$phone1 = $phone2 = $phone3 = '';

	if (strlen($partnerInfo['phoneNumber']) == 10) {
		$phone1 = substr($partnerInfo['phoneNumber'], 0, 3);
		$phone2 = substr($partnerInfo['phoneNumber'], 3, 3);
		$phone3 = substr($partnerInfo['phoneNumber'], 6, 4);
	}
	else {
		$phone2 = substr($partnerInfo['phoneNumber'], 0, 3);
		$phone3 = substr($partnerInfo['phoneNumber'], 3, 4);
	}


	// Close the connection as we've gotten all the information we should need
	closeDB($conn);
?>

	<h3>Update Reallocation Partner</h3>

	<div class="body-content">
		<div id="redistSuccess" class="hoverMsg hoverSuccess" style="display:none;"></div>
		<form id="updateRedistPartner">
			<input type="hidden" value=<?=$_GET['id']?> name="submitUpdateRedist">
			<div class="row">
				<div class="col-sm-3">Partner Name:</div>
				<div class="col-sm-4"><input type="text" name="partnerName" maxlength="45" value="<?=$partnerInfo['name']?>"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">Email:</div>
				<div class="col-sm-4"><input type="email" name="email" value="<?=$partnerInfo['email']?>"></div>
			</div>

			<div class="row">
				<div class="col-sm-3">Phone Number:</div>
				<div class="col-sm-8">(<input class="input-phone input-number" maxlength="3" type="text" name="phone1" value="<?=$phone1?>">)
				<input class="input-phone input-number" maxlength="3" type="text" name="phone2" value="<?=$phone2?>">-
				<input class="input-phone input-number" maxlength="4" type="text" name="phone3" value="<?=$phone3?>"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">Street Address:</div>
				<div class="col-sm-4"><input type="text" name="addressStreet" value="<?=$partnerInfo['address']?>"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">City:</div>
				<div class="col-sm-4"><input type="text" name="addressCity" value="<?=$partnerInfo['city']?>"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">Zip Code:</div>
				<div class="col-sm-4"><input class="input-number"  type="text" maxlength="5" name="addressZip" value="<?=$partnerInfo['zip']?>"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">State:</div>
				<div class="col-sm-4">
					<select name="addressState">
						<?php getStateOptions($partnerInfo['state']); ?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">Notes:</div>
				<div class="col-sm-4"><textarea id='notesInput' class='notes' type='text' maxlength='256' name='notes'><?=$partnerInfo['notes']?></textarea></div>
			</div>
			<div class="clearfix"></div>
			<div class="msg-warning" id="warningMsgs"></div>
	    <input type="submit" class="btn-nav" value="Update" >
	  </form>

		<!-- Invoices -->
		<hr>
		<h4>Reallocations</h4>
	  <div id="datatableContainer">
			<table width='55%' id="iReallocationInvoicesTable" class="display">
				<thead>
					<tr>
						<th width='27%'>Date</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

<?php include 'php/footer.php'; ?>

<script src="js/redistOps.js"></script>
<script type="text/javascript">
	// Set up select box
	$("select").chosen();

	// Call table function
	$('#iReallocationInvoicesTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/reallocInvoiceList.php?id=<?=$_GET['id']?>",
      },
	});

  if (getCookie("newPartner") != "") {
    window.alert("New Parnter Added!");
    removeCookie("newPartner");
  }

  $(".btn-nav").on("click", function(e) {
	  e.preventDefault();
	  $("#warningMsgs").stop(true, true).hide();
	  var fieldData = $("#updateRedistPartner").serialize().trim();
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