<?php
  $pageRestriction = 10;
  include 'php/header.php';
  include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

<h3>Appointment Operations</h3><br>	
	<div class="body-content">
		<div id="datatableContainer">
			<table width='95%' id="apptTable" class="display">
				<thead>
					<tr>
						<th width='5%'></th>
						<th width='30%'>Date</th>
						<th width='30%'># of Appointments</th>
						<th width='10%'># Available</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	
	<br>
	<!-- NEW Date -->
  <?php if ($_SESSION['perms'] >= 99) { ?>
	<form action="ap_ao2.php">
		<input id="NewDate" type="submit" class="btn-nav" name="NewDate" value="New Appointment Date">
    </form>
  <?php } ?>

<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">

	$('#apptTable').DataTable({
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
          "url"       : "php/ajax/apptList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});
	$(document).ready(function(){
		$('#apptTable').on('click', '.btn-edit', function () {
			window.location.assign($(this).attr('value'));
		});
	});
</script>