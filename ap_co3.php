<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 10;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';

  // Set up server connection
  $conn = connectDB();

  // *************************************************
  // Query the database

  // Grab client information
  $sql = "SELECT lastName, numOfAdults, numOfKids, startDate, email, phoneNumber, address, city, state, zip, foodStamps, client.notes, clientType, pets
          FROM client
          JOIN familymember
            ON familymember.clientID = client.ClientID
          WHERE familymember.isHeadOfHousehold = TRUE
          AND client.clientID=" . $_GET['id'];

  $clientInfo = runQueryForOne($conn, $sql);

  $familySize = 1;

  $phone1 = $phone2 = $phone3 = '';

  if (strlen($clientInfo['phoneNumber']) == 10) {
    $phone1 = substr($clientInfo['phoneNumber'], 0, 3);
    $phone2 = substr($clientInfo['phoneNumber'], 3, 3);
    $phone3 = substr($clientInfo['phoneNumber'], 6, 4);
  }
  else {
    $phone2 = substr($clientInfo['phoneNumber'], 0, 3);
    $phone3 = substr($clientInfo['phoneNumber'], 3, 4);
  }

  // Grab family member information
  $sql = "SELECT firstName, lastName, isHeadOfHousehold, birthDate, gender, FamilyMemberID
      FROM familymember
      WHERE clientID=" . $_GET['id'] . "
      AND isDeleted = 0";
  $familyInfo = runQuery($conn, $sql);

  $showDeleteColumn = (count($familyInfo) > 1);

  // Close the connection as we've gotten all the information we should need
  closeDB($conn);

  $familySize 	   = $clientInfo['numOfKids'] + $clientInfo['numOfAdults'];
  $foodStampStatus = $clientInfo['foodStamps'];
  $clientType 	   = $clientInfo['clientType'];
?>

	<div class="body-content">
    <h3>Client: <?= $clientInfo['lastName'] ?> </h3>
    <div id="clientUpdateSuccess" class="hoverMsg hoverSuccess" style="display:none;"></div>
    <form id='updateClient'>
      <input type='hidden' name='UpdateClient' value='<?= $_GET['id'] ?>'>
      <!-- Number of Adults -->
      <div class="row">
        <div class="col-sm-4">Number of Adults:</div>
        <div class="col-sm-8">
          <input style="width:50px;" class="input-number" type="text" name="numAdults" maxlength="2" value=<?= $clientInfo['numOfAdults'] ?> >
        </div>
      </div>
      <!-- Number of Children -->
      <div class="row">
        <div class="col-sm-4">Number of Children:</div>
        <div class="col-sm-8">
          <input style="width:50px;" class="input-number" type="text" name='numKids' maxlength="2" value=<?= $clientInfo['numOfKids'] ?> >
        </div>
      </div>
      <!-- Client Joined Pantry -->
      <div class="row">
        <div class="col-sm-4">Joined Pantry On:</div>
        <div class="col-sm-8">
          <input type="date" name='startDate' value=<?= $clientInfo['startDate'] ?> >
        </div>
      </div>
      <!-- Phone Number -->
      <div class="row">
        <div class="col-sm-4">Phone Number:</div>
        <div class="col-sm-8">(<input class="input-phone input-number" maxlength="3" type="text" name="phone1" value="<?=$phone1?>">)
        <input class="input-phone input-number" maxlength="3" type="text" name="phone2" value="<?=$phone2?>">-
        <input class="input-phone input-number" maxlength="4" type="text" name="phone3" value="<?=$phone3?>"></div>
      </div>
      <!-- Email -->
      <div class="row">
        <div class="col-sm-4">Email:</div>
        <div class="col-sm-8">
          <input id='emailInput' type='email' name='email' value='<?= $clientInfo['email'] ?>'>
        </div>
      </div>
      <!-- Pets -->
      <div class="row" id="iPetRow">
        <div class="col-sm-4">Pets:</div>
        <div class="col-sm-8">
          <select id="iPetSelect" name="pets[]" multiple>
            <?=getPetOptions($clientInfo['pets'])?>
          </select>
        </div>
      </div>
      <!-- ------- Address ---- -->
      <div class="row">
        <div class="col-sm-2"><strong>Address</strong></div>
      </div>
      <div style="border: 2px solid #499BD6; padding:5px;margin-top:3px;">
        <!-- street Address -->
        <div class="row">
          <div class="col-sm-4">Street Address:</div>
          <div class="col-sm-8">
            <input id='addressStreetInput' type='text' name='addressStreet' value='<?= $clientInfo['address'] ?>' >
          </div>
        </div>
        <!-- city -->
        <div class="row">
          <div class="col-sm-4">City:</div>
          <div class="col-sm-8">
            <input id='addressCityInput' type='text' name='addressCity' value='<?= $clientInfo['city'] ?>' >
          </div>
        </div>
        <!-- dropdown for state -->
        <div class="row">
          <div class="col-sm-4">State:</div>
          <div class="col-sm-8">
            <select id="addressStateInput" id="addressState" name="addressState">
              <?php
                getStateOptions($clientInfo['state']);
              ?>
            </select>
          </div>
        </div>
        <!-- zipcode -->
        <div class="row">
          <div class="col-sm-4">Zip Code</div>
          <div class="col-sm-8">
            <input type='number' id='addressZipField' name='addressZip' value=<?= $clientInfo['zip'] ?> >
          </div>
        </div>
      </div>
      <br>
      <!-- Foodstamp Status -->
      <div class="row">
        <div class="col-sm-4">Foodstamp Status:</div>
        <div class="col-sm-8">
          <select id='foodStampsInput' name='foodStamps'>
            <?php
              echo "<option value=-1 " . ($foodStampStatus == -1 ? "selected" : "") . ">Unknown</option>";
              echo "<option value=1 "  . ($foodStampStatus == 1  ? "selected" : "") . ">Yes</option>";
              echo "<option value=0 "  . ($foodStampStatus == 0  ? "selected" : "") . ">No</option>";
            ?>
          </select>
        </div>
      </div>
      <!-- Client Type -->
      <div class="row">
        <div class="col-sm-4">Client Type:</div>
        <div class="col-sm-8">
          <select id='clientTypeInput' name='clientType'>
            <?php echo "
              <option value=0 " . ($clientType == 0 ? "selected" : "") . ">Unknown</option>
              <option value=1 " . ($clientType == 1 ? "selected" : "") . ">Constituent</option>
              <option value=2 " . ($clientType == 2 ? "selected" : "") . ">Member</option>
              <option value=3 " . ($clientType == 3 ? "selected" : "") . ">Resident</option>
              </select>";
            ?>
          </select>
        </div>
      </div>
      <br>
      <!-- Notes -->
      <div class="row">
        <div class="col-sm-4">Notes:</div>
        <div class="col-sm-8">
          <textarea style="width:100%;" class='notes' type='text' name='notes'><?= $clientInfo['notes'] ?></textarea>
        </div>
      </div>

      <div class="msg-warning" id="warningMsgs"></div>
      <input class="btn-nav" type="submit" id="iUpdateBtn" name="UpdateClient" value="Save">
    </form>


    <!-- Family Members Area -->
		<br><h3>Family Members</h3>
    <table class="table">
      <tr>
        <th></th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Birth Date</th>
        <th>Gender</th>
        <th>Head of Household</th>
        <?php if ($showDeleteColumn) { ?>
          <th></th>
        <?php } ?>
      </tr>

      <!-- Go through all listed family members -->
			<?php foreach ($familyInfo as $member) {
        $head = $member['isHeadOfHousehold'] ? "&#10004;" : "";  ?>
        <form action="php/clientOps.php" >
          <input type="hidden" name="memberID" value="<?=$member['FamilyMemberID']?>">
          <input type="hidden" name="clientID" value="<?=$_GET['id']?>">
          <tr>
            <td>
              <button type="submit" class="btn-table btn-edit" name="GoUpdateMember" value="View"><i class="fa fa-eye"> View</i></button>
            </td>
            <td><?=$member['firstName']?></td>
            <td><?=$member['lastName']?></td>
            <td><?=$member['birthDate']?></td>
            <td><?=genderDecoderShort($member['gender'])?></td>
            <td style="color:green;"><?=$head?></td>
            <?php if ($showDeleteColumn) {
              echo "<td>";
              echo "<button id='InactiveMember' name='DeleteMember' class='btn-icon' ";
              if (!$member['isHeadOfHousehold']) {
                echo "type='submit' onclick=\"javascript: return confirm('Are you sure you want to remove this family member?');\")'>";
              }
              else {
                echo "type='button' onclick=\"javascript: alert('Cannot delete the head of household.');\")'>";
              }
              echo "<i class='fa fa-trash'></i></button></td>";
            } ?>
          </tr>
        </form>
      <?php } ?>


		</table>

		<br>
    <!-- New Family Member button -->
    <form action="ap_co4.php">
      <input type="hidden" name="id" value=<?=$_GET['id']?>>
			<input type="hidden" name="lnamedefault" value="<?=$clientInfo['lastName']?>">
      <input class="btn-nav" type="submit" name="newMember" value="New Family Member">
		</form>

		<!-- Invoices / Visits -->
    <hr><br>
    <h3>Appointments</h3>
    <table width='95%' id="invoiceTable" class="display">
      <thead>
        <tr>
          <th width='5%'></th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <form action="#">
      <input type="hidden" name="id" value=<?=$_GET['id']?>>
      <input class="btn-nav" type="submit" name="newApt" value="New Appointment (NYI)" disabled>
		</form>

<?php include 'php/footer.php'; ?>

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
	$("#iUpdateBtn").on("click", function(e) {
    e.preventDefault();
	  $("#warningMsgs").stop(true, true).hide();
	  var fieldData = $("#updateClient").serialize();
	  $.ajax({
	    url: "php/clientOps.php",
	    data: fieldData,
	    type: "POST",
	    dataType: "json",
	    context: document.body,
	    success: function(msg) {
	      if (msg.error == '') {
          $("#clientUpdateSuccess").stop(true,true).hide().html("Update Successful!").show(250).delay(2000).hide(300);
	      }
	      else {
	        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
	      }
	    },
		});
	});

  if (getCookie("newClient") != "") {
    window.alert("New Client Added!");
    removeCookie("newClient");
  }
  if (getCookie("DelFam") != "") {
    window.alert("Family Member Removed!");
    removeCookie("DelFam");
  }
  if (getCookie("Err_DelFam") != "") {
    window.alert("Cannot Remove The Head of Household!");
    removeCookie("Err_DelFam");
  }

	var Params = '?cid=<?=$_GET['id']?>';

	$('#invoiceTable').DataTable({
    "searching"     : false,
    "ordering"      : false,
	  "language"	    : {
      "emptyTable"    : "No Appointments in Database."
                    },
    "ajax"	        : {
      "url"           : "php/ajax/clientApptList.php" + Params,
                    },
	});
	$(document).ready(function(){
		$('#invoiceTable').on('click', '.btn-edit', function () {
			window.location.assign($(this).attr('value'));
		});
	});
</script>
