<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>

  <h3>Reallocations</h3>

	<div class="body-content">

		<div id="datatableContainer">
			<table width='55%' id="iReallocationInvoicesTable" class="table table-striped">
				<thead class="thead-dark">
					<tr>
						<th>Date</th>
						<th>Partner</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

		<!-- NEW Redistribution Invoice -->
    <a href="/RUMCPantry/ap_ro9.php" class="button">New Reallocation</a>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
	if (getCookie("newRedistribution") != "") {
		window.alert("New Reallocation added!");
		removeCookie("newRedistribution");
	}
	if (getCookie("redistributionDeleted") != "") {
		window.alert("Reallocation removed!");
		removeCookie("redistributionDeleted");
	}

	$('#iReallocationInvoicesTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/reallocInvoiceList.php",
      },
	});
</script>