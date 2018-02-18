<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

  $sql = "SELECT donationPartnerID as dpid, name, city
          FROM DonationPartner"; // Eventually check for isDeleted
  $conn = connectDB();

  $donationOptions = runQuery($conn, $sql);
  
  $dpid = isset($_GET['dpid']) ? $_GET['dpid'] : 0;
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

<h3>Add a Donation</h3>
<div class="body-content">

  <div id="donationSuccess" class="hoverMsg" style="display:none;"></div>
  <form id="addDonation" action="" method="post">
    <input type="hidden" value=1 name="createDonation">
    <!-- *************  Name -->
    <div class="row">
      <div class="col-sm-4">Pickup date:</div>
      <div class="col-sm-8">
        <input type="date" id="iPickupDate" name="pickupDate" value="<?=(date('Y-m-d'))?>">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">Network Partner:</div>
      <div class="col-sm-8"><input type="text" id="iNetworkPartner" name="networkPartner" value="RUMC"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Agency:</div>
      <div class="col-sm-8"><input type="text" id="iAgency" name="agency" value="1039a"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Donor:</div>
      <div class="col-sm-8">
        <select data-placeholder="Choose a donor..." class="chosen-select" name="donorName">
          <option value=0></option>
          <?php
            foreach ($donationOptions as $option) {
              $selected = ($option['dpid'] == $dpid) ? " selected " : "";
              echo "<option value=" . $option['dpid'] . " " . $selected . ">" . $option['name'] . " - " . $option['city'] . "</option>";
            }
          ?>
        </select>
      </div>
    </div>

    <!-- Frozen foods -->
    <div style="border: 2px solid darkblue;margin-top:20px;padding:10px;"><h4 class="text-center">Frozen</h4>
      <div class="row">
        <div class="col-sm-6 text-right">Non Meat:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenNonMeat" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Meat and Seafood:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenMeat" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Prepared Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenPrepared" placeholder=0></div>
      </div>
    </div>

    <!-- Refridgerated Foods -->
    <div style="border: 2px solid green;margin-top:20px;padding:10px;"><h4 class="text-center">Refridgerated</h4>
      <div class="row">
        <div class="col-sm-6 text-right">Bakery and Pastries</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refBakery" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Produce:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refProduce" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Dairy and Deli Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refDairyAndDeli" placeholder=0></div>
      </div>
    </div>

    <!-- Assorted stuff -->
    <div style="border: 2px solid brown;margin-top:20px;padding:10px;"><h4 class="text-center">Assorted</h4>
      <div class="row">
        <div class="col-sm-6 text-right">Shelf-Stable:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryShelfStable" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Non-Food Products:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryNonFood" placeholder=0></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Food Drive Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryFoodDrive" placeholder=0></div>
      </div>
    </div>

    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" id="btn_newDonation" value="Create Donation">
  </form>


<?php include 'php/footer.php'; ?>
<script type='text/javascript' src='/RUMCPantry/includes/chosen/chosen.jquery.min.js'></script>
<script src="js/createDonation.js"></script>
