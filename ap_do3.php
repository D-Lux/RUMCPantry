<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
<style>
  .msg-warning {
    display: none;
  }
</style>
<h3>Add Donation Partner</h3>
<div class="body-content">

  <form name="createDonationPartner" action="php/donationOps.php" onSubmit="return validateDonationPartnerAdd()" method="post">
    <!-- *************  Name -->
    <div class="row">
      <div class="col-sm-4">Donation Partner:</div>
      <div class="col-sm-8"><input type="text" id="iPartnerName" name="name" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">City:</div>
      <div class="col-sm-8"><input type="text" id="iCity" name="city" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">State:</div>
      <div class="col-sm-8"><select id="iState" style="margin-left:10px;" name="state"><?=getStateOptions('IL')?></select></div>
    </div>
    <div class="row">
      <div class="col-sm-4">zip:</div>
      <div class="col-sm-8"><input class="addressZipField" type="number" id="iZip" name="zip" maxlength="5"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Address:</div>
      <div class="col-sm-8"><input type="text" id="iAddress" name="address" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Phone Number:</div>
      <div class="col-sm-8"><input type="tel" id="iPhone" name="phoneNumber" maxlength="10"></div>
    </div>
    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" value="Create donation partner" name="createDonationPartner">
</form>

<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>