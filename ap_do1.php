<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

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

<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	
	$('#partnerTable').DataTable({
      "info"          : true,
      "paging"        : true,
      "destroy"       : true,
      "searching"     : true,
      "processing"    : true,
      "serverSide"    : true,
      "orderClasses"  : false,
      "autoWidth"     : false,
      "ordering"      : false,
      "pagingType"    : "full_numbers",
      "ajax": {
          "url"       : "php/ajax/donationPartnerList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
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