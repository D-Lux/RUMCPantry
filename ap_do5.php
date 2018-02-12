<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
  
  $pID      = 0;
  $badLoad  = false;
  if (!isset($_GET['donationPartnerID'])) {
    $badLoad = true;
  }
  else {
    $pID = $_GET['donationPartnerID'];
    $sql = "SELECT name, city, state, zip, address, phoneNumber FROM DonationPartner WHERE donationPartnerID =" . $pID ;
    
    $conn = connectDB();
    $result = runQueryForOne($conn, $sql);
    if ( $result === false ) {
      $badLoad = true;
    }
  }
?>
<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">
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
<h3>Update Donation Partner</h3>
<div class="body-content">   
<div class="body-content">
  <div id="donationSuccess" class="hoverMsg" style="display:none;"></div>
  <form id="updateDonationPartner" action="" method="post">
    <input type="hidden" value=1 name="updateDonationPartnerIndividual">
    <input type="hidden" value=<?=$pID?> name="donationPartnerID">
    <!-- *************  Name -->
    <div class="row">
      <div class="col-sm-4">Donation Partner:</div>
      <div class="col-sm-8"><input type="text" name="name" maxlength="45" value="<?=$result['name']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">City:</div>
      <div class="col-sm-8"><input type="text" name="city" maxlength="45" value="<?=$result['city']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">State:</div>
      <div class="col-sm-8"><select style="margin-left:10px;" name="state"><?=getStateOptions($result['state'])?></select></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Zip Code:</div>
      <div class="col-sm-8"><input class="input-number" type="number" id="addressZipField" name="zip" maxlength="5" value="<?=$result['zip']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Address:</div>
      <div class="col-sm-8"><input type="text" name="address" maxlength="45" value="<?=$result['address']?>"></div>
    </div>
    <div class="row">
      <div class="col-sm-4">Phone Number:</div>
      <div class="col-sm-8">
        (<input class="input-phone input-number" type="number" id="iAreaCode" name="areaCode"  value="<?=substr($result['phoneNumber'], 0, 3)?>">)
        <input class="input-phone input-number" type="number" id="iPhone1" name="phoneNumber1" value="<?=substr($result['phoneNumber'], 3, 3)?>"> -
        <input class="input-phone input-number" type="number" id="iPhone2" name="phoneNumber2" value="<?=substr($result['phoneNumber'], 6, 4)?>"></div>
    </div>
    <div class="msg-warning" id="warningMsgs"></div>
    <input type="submit" class="btn-nav" id="btn_updateDonationPartner" value="Update Donation Partner" name="updateDonationPartner">
  </form>

  <!-- Donations recieved from this partner -->
  <hr><br><h3>Donations</h3>
  <table width='95%' id="donationTable" class="display">
    <thead>
      <tr>
        <th width='5%'></th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

<?php include 'php/footer.php'; ?>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  if (<?=(int)$badLoad?>) {
    window.location.href = '/RUMCPantry/ap_do1.php';
  }
  $('#donationTable').DataTable({
    "info"          : true,
    "paging"        : true,
    "destroy"       : true,
    "searching"     : false,
    "processing"    : true,
    "serverSide"    : true,
    "orderClasses"  : false,
    "autoWidth"     : false,
    "ordering"      : false,
    "pagingType"    : "full_numbers",
    "language"	    : {
    "emptyTable"    : "No donations in database."
                      },
    "ajax"	        : {
        "url"       : "/RUMCPantry/php/ajax/donationList.php?pid=<?=$pID?>",
                      },
    "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
  });
  $('#donationTable').on('click', '.btn-edit', function () {
    window.location.assign($(this).attr('value'));
  });
</script>
<script src="js/createDonation.js">

