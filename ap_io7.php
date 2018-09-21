<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';  
 
// Set filters based on checkboxes during refresh
// Note checkboxes are only in $_POST if checked
  $showDeleted = 0;
  $showZeroPrice = 0;  
  if(isset($_POST['refresh'])){                                         
	if(!empty($_POST['showDeleted'])){
		$showDeleted = $_POST['showDeleted'];
	}	
	if(!empty($_POST['showZeroPrice'])){
		$showZeroPrice = $_POST['showZeroPrice'];
	}	
  }
  $tableFilter = $showDeleted + $showZeroPrice;
  
?>
	
<h3>Item Inventory</h3>

<div class="body-content">
	
<!-- Build Form -->
  <form method="post" action="ap_io7.php">
  	
	<a href="<?=$basePath?>ap_io2.php" class="button">Add an item</a>
	<button type="submit" class="btn-nav" name="refresh" value=1>Refresh</button>
	<br>
	
	<div id='filter'>Filters: &nbsp 
		<?php
		
		echo "<style>";
		echo " 	input[type='checkbox'] {";
		echo "  display: inline-block;}";
		echo "</style>";
		
		echo "<input type='checkbox' id='filterchkbox' name='showDeleted' value='1'"; 
		if ($showDeleted != 0) {
			echo " checked ";
		}
		echo ">Show only deleted items &nbsp &nbsp &nbsp &nbsp"; 
		
		echo "<input type='checkbox' id='filterchkbox' name='showZeroPrice' value='2'";
		if ($showZeroPrice != 0) {
			echo " checked ";
		}
		echo ">Show only items with no price";
		
		?>
	</div>
	
  </form>	
	
	<div id="datatableContainer">
		<table width='95%' id="iItemTable" class="table table-striped">
			<thead class="thead-dark">
				<tr>
					<th width='5%'></th>
					<th width='30%'>Item</th>
					<th width='30%'>Display Name</th>
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

     
<?php include 'php/footer.php'; ?>


<script type="text/javascript">

	$('#iItemTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/itemList.php?filter=<?=$tableFilter?>",
      },
	});

	$(document).ready(function(){
		var deleteMode = <?=$showDeleted?>;
		$('#iItemTable').on('click', '.btn-icon, .btn-edit', function () {
			if ($(this).hasClass('btn-icon')) {
				var mod = "d";
				if (deleteMode == 1) { mod = "r"; }
				if (confirm("Are you sure you want to " + mod + "eactivate this item?")) {
					window.location.assign($(this).attr('value'));
				}
			} else {
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
    // Reactivation cookies
    if (getCookie("itemReactivated") != "") {
			window.alert("Item reactivated!");
			removeCookie("itemReactivated");
		}
    if (getCookie("err_itemReactivated2") != "") {
			window.alert("Item reactivated, failed to reactivate category!");
			removeCookie("err_itemReactivated2");
		}
    if (getCookie("err_itemReactivated1") != "") {
			window.alert("Failed to reactivate item!");
			removeCookie("err_itemReactivated1");
		}


	});

	</script>