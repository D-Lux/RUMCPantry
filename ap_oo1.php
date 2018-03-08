<?php
  $pageRestriction = 99;
	include 'php/header.php';
	include 'php/backButton.php';
?>

    <h3>Order Forms</h3>
	
	<div class="body-content">
	<!-- View order forms -->
  	<div class="row">
      <div class="col-sm-5">
        <a href="/RUMCPantry/cof.php?Small=1" class="button pull-left">View Form: 1-2 / Walk-In</a>
      </div>
      <div class="col-sm-2"></div>
      <div class="col-sm-5">
        <a href="/RUMCPantry/ap_oo2.php?1to2=1" class="button pull-right">Edit Order Forms</a>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-5">
        <a href="/RUMCPantry/cof.php?Medium=1" class="button pull-left">View Form: 3-4</a>
      </div>
      <div class="col-sm-2"></div>
      <div class="col-sm-5">
        <a href="/RUMCPantry/ap_oo6.php?CategoryOrder=1" class="button pull-right">Edit Category Ordering</a>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-5">
        <a href="/RUMCPantry/cof.php?Large=1" class="button pull-left">View Form: 5+</a>
      </div>
    </div>
  
  <div class="clearfix"></div>
		
		
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  if (getCookie("SpecialsSaved") != "") {
    window.alert("Specials Updated!");
    removeCookie("SpecialsSaved");
  }		
</script>
    