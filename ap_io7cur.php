<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

  $showDeleted = isset($_POST['showDeleted']) ? $_POST['showDeleted'] : (int) 0;
  if (isset($_GET['showDeleted'])) {
    $showDeleted = $_GET['showDeleted'];
  }
?>

<h3>Item Inventory</h3>

<?php
if ($showDeleted) {
    echo "<h4>Deactivated Items</h4>";
}
?>
	<div class="body-content">

		<div id="datatableContainer">
			<table width='95%' id="iItemTable" class="table table-striped">
				<thead class="thead-dark">
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
  <a href="<?=$basePath?>ap_io2.php" class="button">Add an item</a>

  <!-- Show Deleted Items -->
  <?php if ($showDeleted) { ?>
    <form method="post" action="ap_io7.php">
      <button type="submit" class="btn-nav" name="showNormal" value=1>Show Items</button>
    </form>
  <?php } else { ?>
    <form method="post" action="ap_io7.php">
      <button type="submit" class="btn-nav" name="showDeleted" value=1>Show Deactivated Items</button>
    </form>
  <?php } ?>

<?php include 'php/footer.php'; ?>
<script type="text/javascript">

	$('#iItemTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/itemList.php?deleted=<?=$showDeleted?>",
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