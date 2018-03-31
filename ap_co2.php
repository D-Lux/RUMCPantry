<?php
  $pageRestriction = 10;
  include 'php/header.php';
  include 'php/backButton.php';
?>

	<h3>Add New Client</h3>
	
	<div class="body-content">
	
		<form id="addClientForm" >
      <input type="hidden" value=1 name="submitClient">
			<div class="row">
        <div class="col-sm-4">First Name:</div>
        <div class="col-sm-4"><input type="text" name="clientFirstName" maxlength="45"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Last Name:</div>
        <div class="col-sm-4"><input type="text" name="clientLastName" maxlength="45"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Number of Adults:</div>
        <div class="col-sm-2"><input style="width:50px;" class="input-number" type="text" name="numAdults" maxlength="2" value="1"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Number of Children:</div>
        <div class="col-sm-2"><input style="width:50px;" class="input-number" type="text" name="numKids" maxlength="2" value="0"></div>
      </div>
      <div class="row" id="iPetRow">
        <div class="col-sm-4">Pets:</div>
        <div class="col-sm-8">
          <select id="iPetSelect" name="pets[]" multiple>
            <?=getPetOptions()?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">Date of Birth:</div>
        <div class="col-sm-4"><input type="date" name="birthDate" min="1900-01-01"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Gender:</div>
        <div class="col-sm-2">
          <select name="gender"> 
            <option value=0>-</option>
            <option value=-1>Male</option> 
            <option value=1>Female</option> 
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">Food Stamp Status:</div>
        <div class="col-sm-4">
          <select name="foodStamps"> 
            <option value=-1>Unknown</option>
            <option value=1>Yes</option>
            <option value=0>No</option> 
				</select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">Client Type:</div>
        <div class="col-sm-4">
          <select name="clientType">
            <option value=0>Unknown</option>
            <option value=1>Constituent</option>
            <option value=2>Member</option>
            <option value=3>Resident</option> 
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">Email:</div>
        <div class="col-sm-4"><input type="email" name="email"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Phone Number:</div>
        <div class="col-sm-2">(<input class="input-number input-phone" type="text" maxlength=3 name="phone1">)</div>
        <div class="col-sm-2"><input class="input-number input-phone" type="text" maxlength=3 name="phone2"> - </div>
        <div class="col-sm-2"><input class="input-number input-phone" type="text" maxlength=4 name="phone3"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Street Address:</div>
        <div class="col-sm-4"><input type="text" name="addressStreet"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">City:</div>
        <div class="col-sm-3"><input type="text" name="addressCity"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">State:</div>
        <div class="col-sm-3">
          <select name="addressState">
            <?=getStateOptions();?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">Zip Code:</div>
        <div class="col-sm-4"><input type="text" name="addressZip"></div>
      </div>
      <div class="clearfix"></div>
			<div class="msg-warning" id="warningMsgs"></div>
			<input type="submit" class="btn-nav" name="submitClient" value="Create" >
		</form>

<?php include 'php/footer.php'; ?>

<script src="js/clientOps.js"></script>

<script type="text/javascript">
  $("select").chosen({
    placeholder_text_multiple: "Select Pet Options",
  }); 
  
  // Handle increasing the size of the div for the multi select
  $("#iPetSelect").on("change", function() {
    var count = $("#iPetSelect :selected").length;
    $("#iPetRow").height((Math.floor(count / 3) * 35) + 35);
  });
  
  // For adding a new donation partner
	$("input[type='submit']").on("click", function(e) {
    e.preventDefault();
	  $("#warningMsgs").stop(true, true).hide();
	  var fieldData = $("#addClientForm").serialize();
	  $.ajax({
	    url: "php/clientOps.php",
	    data: fieldData,
	    type: "POST",
	    dataType: "json",
	    context: document.body,
	    success: function(msg) {
	      if (msg.error == '') {
	        setCookie("newPartner", 1, 30);
	        window.location.assign(basePath + "ap_co3.php?id=" + msg.id);
	      }
	      else {
	        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
	      }
	    },
		});
	});
  

</script>
