<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 10;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

	<h3>New Family Member</h3>

	<div class="body-content">

		<form name="addMember" action="php/clientOps.php" onSubmit="return validateNewClientMember()" method="post">
			<?php echo "<input type='hidden' name='id' value=". $_GET['id'] ." >" ?>

			<div class="inputDiv">

				<!-- Required fields -->
        <div class="row">
          <div class="col-sm-4"><label for="memberFirstNameField" class="required">First Name:</label></div>
          <div class="col-sm-8">
            <input type="text" id="memberFirstNameField" name="memberFirstName" maxlength="45">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4"><label for="memberLastNameField" class="required">Last Name:</label></div>
          <div class="col-sm-8">
            <input type="text" id="memberLastNameField" name="memberLastName" maxlength="45"
              value="<?=$_GET['lnamedefault']?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4"><label for="birthDateInput">Birthday:</label></div>
          <div class="col-sm-8">
            <input id='birthDateInput' type='date' name='birthDate' min='1900-01-01'>
          </div>
        </div>

        <div class="row">

          <div class='col-sm-4 selectionBoxes'>
              <div id='head'>Head of Household:
              <input type='checkbox' id='HoH' name='head' value='head'>
              <label for='HoH' ></label>
            </div>
          </div>

          <div class="col-sm-2"></div>
          <div class="col-sm-2"><label for="genderInput">Gender:</label></div>
          <div class="col-sm-2">
            <select id="genderInput" name="gender">
              <option value=0>-</option>
              <option value=-1>Male</option>
              <option value=1>Female</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4"><label for='notesInput'>Notes:</label></div>
          <div class="col-sm-8">
            <textarea class='notes' type='text' name='notes'></textarea>
          </div>
        </div>
      </div> <!-- /inputDiv -->
			<!-- Submit button -->
      <input class="btn-nav" type="submit" value="Create" name="submitMember">
		</form>

<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>