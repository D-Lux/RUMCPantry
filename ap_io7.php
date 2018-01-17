<?php include 'php/header.php'; ?>
<?php include 'php/backButton.php'; ?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

<h3>Item Inventory</h3>
	
	<div class="body_content">
	
		<div id="datatableContainer">
			<table width='95%' id="iItemTable" class="display">
				<thead>
					<tr>
						<th width='5%'></th>
						<th width='30%'>Item</th>
						<th width='30%'>Category</th>
						<th width='10%'>Aisle</th>
						<th width='10%'>Rack</th>
						<th width='10%'>Shelf</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	
	<!-- New Item -->
	<form method="get" action="ap_io2.php">
		<input type="submit" value="Add an item">
	</form>
	
	<!-- TODO: View 'deleted' items -->
  
<?php include 'php/footer.php'; ?>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">

	$('#iItemTable').DataTable({
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
          "url"       : "php/ajax/itemList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});

	$(document).ready(function(){
		$('#iItemTable').on('click', '.btn_icon, .btn_edit', function () {
			if ($(this).hasClass('btn_icon')) {
				if (confirm("Are you sure you want to deactivate this item?")) {
					window.location.assign($(this).attr('value'));
				}
			}
			else {
				window.location.assign($(this).attr('value'));
			}
		});
		if (getCookie("newItem") != "") {
			window.alert("New Item Added!");
			removeCookie("newItem");
		}
		if (getCookie("itemUpdated") != "") {
			window.alert("Item Updated!");
			removeCookie("itemUpdated");
		}
		if (getCookie("itemDeleted") != "") {
			window.alert("Item Deactivated!");
			removeCookie("itemDeleted");
		}
		if (getCookie("errConnection") != "") {
			window.alert("There was an error connecting to the database!");
			removeCookie("errConnection");
		}
		if (getCookie("errUpdate") != "") {
			window.alert("There was an error when attempting to update!");
			removeCookie("errUpdate");
		}
		if (getCookie("errCreate") != "") {
			window.alert("There was an error when attempting to create!");
			removeCookie("errCreate");
		}
		
	});
		
	</script>