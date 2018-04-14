<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

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

  <a href="<?=$basePath?>ap_do2.php" class="button">Add a donation</a>
  <a href="<?=$basePath?>ap_do3.php" class="button">Add a donation partner</a>
    
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