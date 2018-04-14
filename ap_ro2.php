<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

  $inactive = isset($_GET['ShowInactive']);

  $pageTitle = "Reallocation Partners";
  $pageBtnName = "ShowInactive";
  $pageBtnText = "View Inactive Partners";
  $tblBtnFnct  = "deactivate";

  if ($inactive) {
  	$pageTitle .= " - Inactive";
  	$pageBtnName = "ShowActive";
  	$pageBtnText = "View Active Partners";
  	$tblBtnFnct  = "reactivate";
  }
?>
  <h3><?=$pageTitle?></h3>

	<div class="body-content">
		<div id="datatableContainer">
			<table width='95%' id="iReallocationTable" class="table table-striped">
				<thead class="thead-dark">
					<tr>
						<?php if (!$inactive) { ?>
							<th width='5%'></th>
					 	<?php } ?>
						<th width='27%'>Partner</th>
						<th width='5%'>Email</th>
						<th width='15%'>Phone Number</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	<!-- NEW Client -->
  <a href="<?=$basePath?>ap_ro3.php" class="button">New Partner</a>

	<!-- View In/Active Clients -->
	<form method="get">
		<input type="submit" class="btn-nav" name="<?=$pageBtnName?>" value="<?=$pageBtnText?>">
  </form>


	<div id="errorLog"></div>

<?php include 'php/footer.php'; ?>


<script type="text/javascript">
  if (getCookie("updatePartner") != "") {
    window.alert("Partner information updated!");
    removeCookie("updatePartner");
  }
  if (getCookie("redistToggled") != "") {
    window.alert("Partner <?=$tblBtnFnct?>d!");
    removeCookie("redistToggled");
  }

  var Params = "";
  <?php if ($inactive) { ?>
  	Params += "?deleted=1";
  <?php } ?>


  $('#iReallocationTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/reallocClientList.php" + Params,
      },
	});

	$(document).ready(function(){
		$('#iReallocationTable').on('click', '.btn-icon, .btn-edit', function () {
			if ($(this).hasClass('btn-icon')) {
				if (confirm("Are you sure you want to <?=$tblBtnFnct?> this client?")) {
					window.location.assign($(this).attr('value'));
				}
			}
			else {
				window.location.assign($(this).attr('value'));
			}
		});
	});
</script>