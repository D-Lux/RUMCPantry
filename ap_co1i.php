<?php
  $pageRestriction = 10; 
  include 'php/header.php';
  include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

  <h3>Inactive Clients</h3>
	
	<div class="body-content">		
		<div id="datatableContainer">
			<table width='95%' id="iClientTable" class="display">
				<thead>
					<tr>
						<th>Name</th>
						<th>Size</th>
						<th>Email</th>
						<th>Phone Number</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	<br>
	
	<!-- View Active Clients -->
	<form action="ap_co1.php">
		<input class="btn-nav" type="submit" name="ShowActive" value="View Active Clients">
    </form>

	<div id="errorLog"></div>
	
<?php include 'php/footer.php'; ?>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	if (getCookie("clientUpdated") != "") {
		window.alert("Client data updated!");
		removeCookie("clientUpdated");
	}
	$('#iClientTable').DataTable({
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
          "url"       : "php/ajax/clientList.php?deleted=1",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});

	$(document).ready(function(){
		$('#iClientTable').on('click', '.btn-icon, .btn-edit', function () {
			if ($(this).hasClass('btn-icon')) {
				if (confirm("Are you sure you want to reactivate this client?")) {
					window.location.assign($(this).attr('value'));
				}
			}
			else {
				window.location.assign($(this).attr('value'));
			}
		});
	});
</script>
