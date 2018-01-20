<?php 
include 'php/header.php';
include 'php/backButton.php';

$showDeleted = isset($_POST['showDeleted']) ? $_POST['showDeleted'] : (int) 0;
if (isset($_GET['showDeleted'])) {
  $showDeleted = $_GET['showDeleted'];
}
?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

<h3>Inventory Categories</h3>
	  
<?php
if ($showDeleted) {
    echo "<h4>Deactivated Items</h4>";
}
?>
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
    <input class='btn-nav' type="submit" value="Add a category">
  </form>
	
    <!-- Show Deleted Items -->
  <?php if ($showDeleted) { ?>
    <form method="post" action="ap_io8.php">
      <button type="submit" class="btn-nav" name="showNormal" value=1>Show Categories</button>
    </form>
  <?php } else { ?>
    <form method="post" action="ap_io8.php">
      <button type="submit" class="btn-nav" name="showDeleted" value=1>Show Deactivated Categories</button>
    </form>
  <?php } ?>
  
<?php include 'php/footer.php'; ?>
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
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
          "url"       : "php/ajax/categoryList.php?deleted=<?=$showDeleted?>",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
	});

	$(document).ready(function(){
		$('#iCategoryTable').on('click', '.btn_icon, .btn_edit', function () {
			if ($(this).hasClass('btn_icon')) {
        if ($(this).hasClass('btn_reactivate')) {
          if (confirm("Are you sure you want to reactivate this category?")) {
            window.location.assign($(this).attr('value'));
          }
        }
        else {
          if (confirm("Are you sure you want to deactivate this category?")) {
            window.location.assign($(this).attr('value'));
          }
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
    if (getCookie("categoryReactivated") != "") {
			window.alert("Category Reactivated!");
			removeCookie("categoryReactivated");
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
    if (getCookie("conErr") != "") {
      window.alert("There was an error attempting to connect to the database.");
			removeCookie("conErr");
    }
    if (getCookie("reactivateError") != "") {
      window.alert("There was an error attempting to connect to the database.");
			removeCookie("reactivateError");
    }
    if (getCookie("CatExists") != "") {
      window.alert("That category already exists.");
			removeCookie("CatExists");
    }
    
    

	});
		
	</script>
    