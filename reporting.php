<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
  
   $startDate = date('Y-m-d', strtotime('first day of last month'));
  $endDate = date('Y-m-d', strtotime('last day of last month'));
?>

<h3>Reporting</h3>

<div class='body-content'>
  <div class='row'>
    <div class="col-md-2 text-right">Start Date:</div>
    <div class="col-md-5">
      <input id='startDate' type='Date' value='<?=$startDate?>'>
    </div>
  </div>
  <div class ="row">
    <div class="col-md-2 text-right">End Date:</div>
    <div class="col-md-5">
      <input id='endDate' type='date' value='<?=$endDate?>'>
    </div>
  </div>
  <div class="clearfix"></div>
  <hr><br>
  
  <div id='reportData'></div>
<?php include 'php/footer.php'; ?>

<script src="js/reportingOps.js"></script>