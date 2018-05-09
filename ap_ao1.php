<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 10;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

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
    <a href="<?=$basePath?>ap_ao2.php" class="button">New Appointment Date</a>
  <?php } ?>

<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>
<script type="text/javascript">

	$('#apptTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/apptList.php",
      },
	});
	$(document).ready(function(){
		$('#apptTable').on('click', '.btn-edit', function () {
			window.location.assign($(this).attr('value'));
		});
	});
</script>