<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';

  $inactive = isset($_GET['ShowInactive']);

  $pageTitle = "Reallocation Items";
  $pageBtnName = "ShowInactive";
  $pageBtnText = "View Inactive Items";
  $tblBtnFnct  = "deactivate";

  if ($inactive) {
    $pageTitle .= " - Inactive";
    $pageBtnName = "ShowActive";
    $pageBtnText = "View Active Items";
    $tblBtnFnct  = "reactivate";
  }
?>
    <h3><?=$pageTitle?></h3>
	<div class="body-content">

	<table class="table table-striped" id="iReallocItemTable">
    <thead class="thead-dark">
      <tr>
        <th></th>
			  <th>Item</th>
        <th>Price</th>
        <th>Weight</th>
        <th></th>
      </tr>
    </thead>
  </table>

	<!-- NEW Redistribution Item -->
  <a href="<?=$basePath?>ap_ro6.php" class="button">New Reallocation Item</a>

	<!-- View Deactivated Items -->
	<form method="get">
		<input type="submit" class="btn-nav" name="<?=$pageBtnName?>" value="<?=$pageBtnText?>">
    </form>

<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  if (getCookie("newRedistItem") != "") {
    window.alert("New Item Added!");
    removeCookie("newRedistItem");
  }
  if (getCookie("redistToggled") != "") {
    window.alert("Redistribution item <?=$tblBtnFnct?>d!");
    removeCookie("redistToggled");
  }

  var Params = "";
  <?php if ($inactive) { ?>
    Params += "?deleted=1";
  <?php } ?>

  $('#iReallocItemTable').DataTable({
      "columnDefs"    : [
                      {"orderable" : false, "targets": [0,4]},
                    ],
      "order"         : [ 1, 'DESC' ],
      "ajax": {
          "url"       : "php/ajax/reallocItemList.php" + Params,
      },
  });

  $('#iReallocItemTable').on('click', '.btn-icon, .btn-edit', function () {
    if ($(this).hasClass('btn-icon')) {
      if (confirm("Are you sure you want to <?=$tblBtnFnct?> this client?")) {
        window.location.assign($(this).attr('value'));
      }
    }
    else {
      window.location.assign($(this).attr('value'));
    }
  });
</script>