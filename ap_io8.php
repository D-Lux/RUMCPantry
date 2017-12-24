<?php 
include 'php/header.php';
include 'php/backButton.php'; 
?>

<script type="text/javascript" charset="utf8" src="includes/jquery-3.2.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

<h3>Item Inventory</h3>
	
	<div class="body_content">
	
		<div id="datatableContainer">
			<table width='90%' id="iCategoryTable" class="display">
				<thead>
					<tr>
						<th width='5%'></th>
						<th width='30%'>Name</th>
						<th width='20%'>QTY Small</th>
						<th width='20%'>QTY Medium</th>
						<th width='20%'>QTY Large</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	
	<!-- New Item -->
	<form method="get" action="ap_io4.php">
        <input type="submit" value="Add a category">
    </form>
	
	<!-- TODO: View 'deleted' items -->
</div><!-- /body_content -->
</div><!-- /content -->	
</body>

<script type="text/javascript">

	$('#iCategoryTable').DataTable({
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
          "url"       : "php/ajax/categoryList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});

	$(document).ready(function(){
		$('#iCategoryTable').on('click', '.btn_icon, .btn_edit', function () {
			if ($(this).hasClass('btn_icon')) {
				if (confirm("Are you sure you want to deactivate this category?")) {
					window.location.assign($(this).attr('value'));
				}
			}
			else {
				window.location.assign($(this).attr('value'));
			}
		});
		if (getCookie("newCategory") != "") {
			window.alert("Category Added!");
			removeCookie("newCategory");
		}
		if (getCookie("categoryUpdated") != "") {
			window.alert("Category Updated!");
			removeCookie("categoryUpdated");
		}
		if (getCookie("categoryDeleted") != "") {
			window.alert("Category Deactivated!");
			removeCookie("categoryDeleted");
		}
		if (getCookie("delError") != "") {
			window.alert("There was an error when attempting to delete the category.");
			removeCookie("delError");
		}
		if (getCookie("errCreate") != "") {
			window.alert("There was an error when attempting to create the category.");
			removeCookie("errCreate");
		}
		if (getCookie("errUpdate") != "") {
			window.alert("There was an error when attempting to update the category.");
			removeCookie("errUpdate");
		}
		
		
		
	});
		
	</script>
	
	
</html>
    