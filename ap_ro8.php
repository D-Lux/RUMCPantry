<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>

    <h3>Reallocations</h3>



	<div class="body-content">

		<div id="datatableContainer">
			<table width='55%' id="iReallocationInvoicesTable" class="display">
				<thead>
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
		<form action="php/redistOps.php" method="post" >
			<input type="submit" name="newRedistInvoice" value="New Reallocation">
	  </form>

	  <form method="get">
			<input type="submit" class="btn-nav" name="<?=$pageBtnName?>" value="<?=$pageBtnText?>">
  	</form>

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