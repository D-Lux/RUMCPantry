<?php
	include('../utilities.php');

	// AJAX call to pull up order form adjustment tables
	if (!isset($_GET['catid'])) {
    die("No value set");
  }

  $conn = connectDB();
  // Show three boxes for quantities (small, medium large
  // Show table of all items with adjustment boxes and locations

  // Get category information
  $sql = "SELECT small, medium, large
          FROM category
          WHERE categoryID = {$_GET['catid']}";
  $catQs = runQueryForOne($conn, $sql);

  // Get item information
  $sql = "SELECT itemID, rack, shelf, aisle, itemName, small, medium, large
          FROM item
          WHERE isDeleted=0
          AND categoryID = {$_GET['catid']}
          ORDER BY aisle, rack, shelf, itemName";
  $itemQs = runQuery($conn, $sql);

  closeDB($conn);

?>
  <div class="row">
    <div class="col-sm-3 text-right">Small</div>
    <div class="col-sm-9">
      <input type="text" class="input-number CQty" style="width:60px;" maxlength=2 id="small" value=<?=$catQs['small']?>>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-3 text-right">Medium</div>
    <div class="col-sm-9">
      <input type="text" class="input-number CQty" style="width:60px;" maxlength=2 id="medium" value=<?=$catQs['medium']?>>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-3 text-right">Large</div>
    <div class="col-sm-9">
      <input type="text" class="input-number CQty" style="width:60px;" maxlength=2 id="large" value=<?=$catQs['large']?>>
    </div>
  </div>

  <table class="table">
    <thead class="thead-dark table-striped">
      <tr>
        <th>Item Name</th>
        <th>Aisle</th>
        <th>Rack</th>
        <th>Shelf</th>
        <th>Small</th>
        <th>Medium</th>
        <th>Large</th>
      </tr>
    </thead>
  <?php foreach ($itemQs as $item) { ?>
    <tr>
      <td><?=$item['itemName']?></td>
      <td><?=aisleDecoder($item['aisle'])?></td>
      <td><?=rackEncoder($item['rack'])?></td>
      <td><?=shelfDecoder($item['shelf'])?></td>
      <td><input type="text" class="input-number IQty" style="width:40px;" maxlength=2 id="s<?=$item['itemID']?>" value=<?=$item['small']?>></td>
      <td><input type="text" class="input-number IQty" style="width:40px;" maxlength=2 id="m<?=$item['itemID']?>" value=<?=$item['medium']?>></td>
      <td><input type="text" class="input-number IQty" style="width:40px;" maxlength=2 id="l<?=$item['itemID']?>" value=<?=$item['large']?>></td>
    </tr>
  <?php } ?>
  </table>















