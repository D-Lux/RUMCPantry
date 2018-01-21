<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">
	
<h3>Active Clients</h3>
	
	<div class="body-content">
	
	
		<div id="datatableContainer">
			<table width='95%' id="iClientTable" class="display">
				<thead>
					<tr>
						<th width='5%'></th>
						<th width='27%'>Name</th>
						<th width='5%'>Size</th>
						<th width='15%'>Email</th>
						<th width='23%'>Phone Number</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	
	<!-- NEW Client -->
	<form action="ap_co2.php">
		<input id="CreateNew" class="btn-nav" type="submit" name="GoNewClient" value="New Client">
    </form>
	
	<!-- View Inactive Clients -->
	<form action="ap_co1i.php">
		<input type="submit" class="btn-nav" name="ShowInactive" value="View Inactive Clients">
    </form>
    
<?php include 'php/footer.php'; ?>

<script src="js/clientOps.js"></script>
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
          "url"       : "php/ajax/clientList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});

	$(document).ready(function(){
		$('#iClientTable').on('click', '.btn-icon, .btn-edit', function () {
			if ($(this).hasClass('btn-icon')) {
				if (confirm("Are you sure you want to deactivate this client?")) {
					window.location.assign($(this).attr('value'));
				}
			}
			else {
				window.location.assign($(this).attr('value'));
			}
		});
	});
		
</script>