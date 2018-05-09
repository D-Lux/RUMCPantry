<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';

  $donationID = $_GET['donationID'];
  $conn = connectDB();
  // Get donation partner names
  $sql = "SELECT donationPartnerID as dpid, name, city
          FROM donationpartner
          ORDER BY name ASC";
  $donationOptions = runQuery($conn, $sql);

  // Get donation details
  $donation = [];
  $sql = "SELECT donationPartnerID, dateOfPickup, networkPartner, agency, frozenNonMeat, frozenMeat, frozenPrepared, refBakery, refDairyAndDeli, refProduce, dryFoodDrive, dryNonFood, dryShelfStable FROM donation WHERE donationID = ". $donationID;

  $result = runQueryForOne($conn, $sql);
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

<h3>View/Update Donation</h3>
<div class="body-content">
  <div id="donationSuccess" class="hoverMsg" style="display:none;"></div>
  <form id="updateDonation" action="" method="post">
    <input type="hidden" value=1 name="updateDonation">
    <input type="hidden" value=<?=$donationID?> name="donationID">
    <!-- *************  Name -->
    <div class="row">
      <div class="col-sm-4">Pickup date:</div>
      <div class="col-sm-8">
        <input type="date" id="iPickupDate" name="pickupDate" value="<?=$result['dateOfPickup']?>">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">Network Partner:</div>
      <div class="col-sm-8"><input type="text" id="iNetworkPartner" name="networkPartner" value="<?=$result['networkPartner']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Agency:</div>
      <div class="col-sm-8"><input type="text" id="iAgency" name="agency" value="<?=$result['agency']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Donor:</div>
      <div class="col-sm-8">
        <select data-placeholder="Choose a donor..." class="chosen-select" name="donorName">
          <option value=0></option>
          <?php
            foreach ($donationOptions as $option) {
              $selected = ($option['dpid'] == $result['donationPartnerID']) ? ' selected ' : '' ;
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
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenNonMeat" value=<?=$result['frozenNonMeat']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Meat and Seafood:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenMeat" value=<?=$result['frozenMeat']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Prepared Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="frozenPrepared" value=<?=$result['frozenPrepared']?>></div>
      </div>
    </div>

    <!-- Refridgerated Foods -->
    <div style="border: 2px solid green;margin-top:20px;padding:10px;"><h4 class="text-center">Refridgerated</h4>
      <div class="row">
        <div class="col-sm-6 text-right">Bakery and Pastries</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refBakery" value=<?=$result['refBakery']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Produce:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refProduce" value=<?=$result['refProduce']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Dairy and Deli Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="refDairyAndDeli" value=<?=$result['refDairyAndDeli']?>></div>
      </div>
    </div>

    <!-- Assorted stuff -->
    <div style="border: 2px solid brown;margin-top:20px;padding:10px;"><h4 class="text-center">Assorted</h4>
      <div class="row">
        <div class="col-sm-6 text-right">Shelf-Stable:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryShelfStable" value=<?=$result['dryShelfStable']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Non-Food Products:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryNonFood" value=<?=$result['dryNonFood']?>></div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-right">Food Drive Foods:</div>
        <div class="col-sm-6"><input class="input-number" type="text" maxlength=6 name="dryFoodDrive" value=<?=$result['dryFoodDrive']?>></div>
      </div>
    </div>

    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" id="btn_updateDonation" value="Update Donation">
  </form>
 </div>

<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>
