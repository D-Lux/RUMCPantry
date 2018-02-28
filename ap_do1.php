<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>

<h3>Donation Operations</h3>
<div class="body-content">
	
	
  <div id="datatableContainer">
    <table width='95%' id="partnerTable" class="display">
      <thead>
        <tr>
          <th width='5%'></th>
          <th width='27%'>Name</th>
          <th width='15%'>City</th>
          <th width='23%'>Phone Number</th>
          <!--<th width='5%'></th>-->
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <form method="get" action="ap_do2.php">
    <input class="btn-nav" type="submit" value="Add a donation">
  </form>

  <form method="get" action="ap_do3.php">
    <input class="btn-nav" type="submit" value="Add a donation partner">
  </form>

    
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
	
	$('#partnerTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/donationPartnerList.php",
      },
	});
  $('#datatableContainer').on('click', '.btn-icon, .btn-edit', function () {
    if ($(this).hasClass('btn-icon')) {
      if (confirm("Are you sure you want to deactivate this partner?")) {
        window.location.assign($(this).attr('value'));
      }
    }
    else {
      window.location.assign($(this).attr('value'));
    }
  });
		
</script>