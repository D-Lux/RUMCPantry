<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
<style>
  .msg-warning {
    display: none;
  }
  p {
    margin: 5px !important;
    padding: 0px !important;
    color: red;
  }
  #donationSuccess {
    top: 25%;
    left: 50%;
    width: 100%;
    height: 40px;
  }
</style>
<h3>Add Donation Partner</h3>
<div class="body-content">
  <div id="donationSuccess" class="hoverMsg" style="display:none;"></div>
  <form id="createDonationPartner" action="php/donationOps.php" method="post">
    <input type="hidden" value=1 name="createDonationPartner">
    <!-- *************  Name -->
    <div class="row">
      <div class="col-sm-4">Donation Partner:</div>
      <div class="col-sm-8"><input type="text" name="name" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">City:</div>
      <div class="col-sm-8"><input type="text" name="city" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">State:</div>
      <div class="col-sm-8"><select style="margin-left:10px;" name="state"><?=getStateOptions('IL')?></select></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Zip Code:</div>
      <div class="col-sm-8"><input class="input-number" type="text" id="addressZipField" name="zip" maxlength="5"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Address:</div>
      <div class="col-sm-8"><input type="text" name="address" maxlength="45"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Phone Number:</div>
      <div class="col-sm-8">
        (<input class="input-phone input-number" type="text" id="iAreaCode" name="areaCode" maxlength="3">)
        <input class="input-phone input-number" type="text" id="iPhone1" name="phoneNumber1" maxlength="3"> -
        <input class="input-phone input-number" type="text" id="iPhone2" name="phoneNumber2" maxlength="4"></div>
    </div>
    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" id="btn_createDonationPartner" value="Create donation partner" name="createDonationPartner">
</form>

<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>