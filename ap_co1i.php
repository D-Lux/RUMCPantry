<?php 
include 'php/header.php';
include 'php/backButton.php';
?>

<script type="text/javascript" charset="utf8" src="includes/jquery-3.2.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

<style>
	.btn_icon {
		color:green;
	}
	
</style>

<script>
	if (getCookie("clientUpdated") != "") {
		window.alert("Client data updated!");
		removeCookie("clientUpdated");
	}		
</script>
	
    <h3>Inactive Clients</h3>
	
	<div class="body_content">		
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
		<input type="submit" name="ShowActive" value="View Active Clients">
    </form>

	<div id="errorLog"></div>
	
	</div><!-- /body_content -->
	</div><!-- /content -->	
</body>

<script type="text/javascript">

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
		$('#iClientTable').on('click', '.btn_icon, .btn_edit', function () {
			if ($(this).hasClass('btn_icon')) {
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

</html>
