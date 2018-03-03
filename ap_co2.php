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
        <div class="col-sm-2"><input style="width=20px;" class="input-number" type="text" name="numAdults" maxlength="2" value="1"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Number of Children:</div>
        <div class="col-sm-2"><input class="input-number" type="text" name="numKids" maxlength="2" value="0"></div>
      </div>
      <div class="row">
        <div class="col-sm-4">Date of Birth:</div>
        <div class="col-sm-4"><input type="date" id="birthDateField" name="birthDate" min="1900-01-01"></div>
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
        <div class="col-sm-2"><input class="input-number input-phone" type="text" maxlength=3 name="phone2">-</div>
        <div class="col-sm-2"><input class="input-number input-phone" type="text" maxlength=3 name="phone3"></div>
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
	        window.location.assign("/RUMCPantry/ap_ro4.php?id=" + msg.id);
	      }
	      else {
	        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
	      }
	    },
		});
	});
  
  function validateNewClient() {

    var response = "";
	var clientFirstName =  document.getElementById("clientFNameField").value;
    var clientLastName = document.getElementById("clientLNameField").value;
    var numAdults = document.getElementById("numAdultsField").value;
	var DOB = document.getElementById("birthDateField").value;
    var errors = 0;

	if (clientFirstName == "" || clientFirstName.length == 0 || clientFirstName == null) {
        getElementAndColorIt("clientFirstName", "red");
        errors++;
        response += "First Name field is empty. \n"
    }
    if (clientLastName == "" || clientLastName.length == 0 || clientLastName == null) {
        getElementAndColorIt("clientLastName", "red");
        errors++;
        response += "Last Name field is empty. \n"
    }
    if (numAdults == "" || numAdults.length == 0 || numAdults == null || numAdults == "0") {
        getElementAndColorIt("numAdults", "red");
        errors++;
        response += "Clients must have at least one adult. \n"
    }
	if(!Date.parse(DOB)){
		getElementAndColorIt("birthDate", "red");
        errors++;
        response += "Date of Birth is not set. \n"
	}
	
    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}
</script>
