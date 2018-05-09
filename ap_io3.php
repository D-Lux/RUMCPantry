<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>
<style>
input[type="text"] {
    width: 500px;
}
</style>

<h3>Update Item</h3>
<div class="body-content">
<?php
  $itemID = $_GET['itemID'];

   // Connect to the database
  $conn = connectDB();


  $badLoad = false;

  $sql = "SELECT item.isDeleted, itemID, itemName, displayName, price, item.small, item.medium, item.large, aisle, rack, shelf, name
          FROM item
          JOIN category
            ON category.categoryID = item.categoryID
          WHERE itemID = " . $itemID . "
          LIMIT 1 ";
  $result = runQueryForOne($conn,$sql);

  if ($result === false || $result['isDeleted'] == true) {
    $badLoad = true;
  }
  else {
    $itemName    = $result["itemName"];
    $displayName = $result["displayName"];
    $price       = $result["price"];
    $small 	     = $result["small"];
    $medium      = $result["medium"];
    $large       = $result["large"];
    $aisle       = $result["aisle"];;
    $rack        = $result["rack"];;
    $shelf       = $result["shelf"];;
    $categoryName = $result["name"];
  }

	closeDB($conn);
?>

  <script type="text/javascript">
    if (<?=(int)$badLoad?>) {
      window.location.href = 'ap_io7.php';
    }
  </script>

	<form name="addItem" action="php/itemOps.php" onSubmit="return validateItemAdd()" method="post">
    <input type="hidden" name="itemID" value="<?= $itemID ?>">
    <div class="row">
      <div class="col-sm-4"><label class="required categoryField">Category: </label></div>
      <div class="col-sm-8">
        <?php
          createDatalist_i($categoryName,"categories","category","name","category", false);
        ?>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4"><label class="required itemField">Item Name: </label></div>
      <div class="col-sm-8">
        <?php
          createDatalist_i($itemName,"itemNames","item","itemName","itemName", true);
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4"><label class="required displayField">Display Name: </label></div>
      <div class="col-sm-8">
        <?php
          createDatalist_i($displayName,"displayNames","item","displayName","displayName", true);
        ?>
      </div>
    </div>
    <div style="border: 2px solid #499BD6; padding:5px;margin-top:20px;">
      <div class="row">
        <div class="col-sm-2">Location:</div>
      </div>
      <div class="row">
        <!-- Aisle -->
        <div class="col-sm-1">Aisle:</div>
        <div class="col-sm-2">
          <select name="aisle">
            <option value=0>-</option>
            <?php
              for ($i = MIN_AISLE; $i <= MAX_AISLE; $i++) {
                echo "<option " . (($aisle==$i) ? 'selected' : '') . " value=" . $i . ">" . aisleDecoder($i) . "</option>";
              }
            ?>
          </select>
        </div>
        <!-- Rack -->
        <div class="col-sm-1">Rack:</div>
        <div class="col-sm-2">
          <select name="rack">
            <option value=0>-</option>
            <?php
              for ($i = MIN_RACK; $i <= MAX_RACK; $i++) {
                echo "<option " . (($rack==$i) ? 'selected' : '') . " value=" . $i . ">" . rackDecoder($i) . "</option>";
              }
            ?>
          </select>
        </div>
        <div class="col-sm-1">Shelf:</div>
        <div class="col-sm-2">
          <select name="shelf">
            <option value=0>-</option>
            <?php
              for ($i = MIN_SHELF; $i <= MAX_SHELF; $i++) {
                echo "<option " . (($shelf==$i) ? 'selected' : '') . " value=" . $i . ">" . shelfDecoder($i) . "</option>";
              }
            ?>
          </select>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-4">Price:</div>
      <div class="col-sm-8">
        <span>$</span>
        <input  style="width: 8em;" type="number" min="0" value="<?= $price ?>" step="0.01" name="price">
      </div>
    </div>
    <br>

    <!-- QTY to take -->
    <div style="border: 2px solid #499BD6; padding:5px;">
      <div class="row">
        <div class="col-sm-4">Order Form Quantities:</div>
      </div>
      <div class="row">
        <div class="col-sm-1">Small:</div>
        <div class="col-sm-2">
          <input type="number" min="0" max="20" value="<?= $small ?>" name="small" />
        </div>
        <div class="col-sm-1">Medium:</div>
        <div class="col-sm-2">
          <input type="number" min="0" max="20" value="<?= $medium ?>" name="medium" />
        </div>
        <div class="col-sm-1">Large:</div>
        <div class="col-sm-2">
          <input type="number" min="0" max="20" value="<?= $large ?>" name="large" />
        </div>
      </div>
    </div>
    <input type="submit" class='btn-nav' value="Update" name="updateItemIndividual">
  </form>

<?php include 'php/footer.php'; ?>
<script src="js/createItem.js"></script>