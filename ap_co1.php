<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
  
  $inactive = isset($_GET['ShowInactive']);

  $pageTitle = "Client List";
  $pageBtnName = "ShowInactive";
  $pageBtnText = "View Inactive Clients";
  $tblBtnFnct  = "deactivate";

  if ($inactive) {
  	$pageTitle .= " - Inactive";
  	$pageBtnName = "ShowActive";
  	$pageBtnText = "View Active Clients";
  	$tblBtnFnct  = "reactivate";
  }
?>
	
<h3><?=$pageTitle?></h3>
	
	<div class="body-content">
	
		<div id="datatableContainer">
			<table width='95%' id="iClientTable" class="display">
				<thead>
					<tr>
            <?php if (!$inactive) { ?>
              <th width='5%'></th>
            <?php } ?>
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
	<!-- Swap between active and inactive -->
	<form type="get">
		<input type="submit" class="btn-nav" name="<?=$pageBtnName?>" value="<?=$pageBtnText?>">
   </form>
    
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  if (getCookie("clientUpdated") != "") {
			window.alert("Client data updated!");
			removeCookie("clientUpdated");
	}
  var Params = "";
  <?php if ($inactive) { ?>
    Params = "?deleted=1";
  <?php } ?>
	$('#iClientTable').DataTable({
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/clientList.php" + Params,
      },
	});

	$(document).ready(function(){
		$('#iClientTable').on('click', '.btn-icon, .btn-edit', function () {
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