<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
<script src="js/clientOps.js"></script>
<script type="text/javascript" charset="utf8" src="includes/jquery-3.2.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>


<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

	<script>
		if (getCookie("clientUpdated") != "") {
			window.alert("Client data updated!");
			removeCookie("clientUpdated");
		}		
	</script>
	
    <h3>Active Clients</h3>
	
	<div class="body_content">
	
	
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
		<input id="CreateNew" type="submit" name="GoNewClient" value="New Client">
    </form>
	
	<!-- View Inactive Clients -->
	<form action="ap_co1i.php">
		<input type="submit" name="ShowInactive" value="View Inactive Clients">
    </form>
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
          "url"       : "php/ajax/clientList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
      //columnDefs      : [
      //                   {"className": "dt-center", "targets": (0,2,3)}
      //                  ],
      //"lengthMenu"    : [[ 10, 20, 50, 100, -1], [ 10, 20, 50 100, "All"]],
      //"pageLength"    : 10,
	});

	$(document).ready(function(){
		$('#iClientTable').on('click', '.btn_icon, .btn_edit', function () {
			if ($(this).hasClass('btn_icon')) {
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
	
	
</html>