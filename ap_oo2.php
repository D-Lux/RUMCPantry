<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

  $conn     = connectDB();

  // Create our query from the Item and Category tables
	// Item Name, $familyType, category name, category family type, itemid
  $sql = "SELECT name, categoryID as id
          FROM category
          WHERE isDeleted=0
          AND name<>'Specials'
          AND name<>'redistribution'
          ORDER BY name";

  $categories = runQuery($conn, $sql);
  if (empty($categories)) {
    echo "No categories were in the database.";
    die();
  }
  $firstCat = current($categories)['id'];
?>

<h3>Edit Order Forms</h3>
<div id="errMsgs" style="display:none;color:red;"></div>
<div id="msgLog" class="hoverSuccess hoverMsg" style="display:none;"></div>

<div class="body-content">

<select id="catSelector">
  <?php foreach ($categories as $category) { ?>
    <option value=<?=$category['id']?>><?=$category['name']?></option>
  <?php } ?>
</select>


<div id="categoryHolder"></div>



<?php include 'php/footer.php'; ?>
<script type="text/javascript">
  $("select").on("change", function() {
    $("#errMsgs").hide(200);
  });
  $(".tablinks").on("click", function() {
    $("#errMsgs").hide(200);
  });

  $("#catSelector").chosen();

  // Load the first tab
  $.ajax({
    url: basePath + "php/ajax/editOrderForm.php?catid=" + $("#catSelector").val(),
    success: function(response) {
      $("#categoryHolder").html(response);
      checkQuantitySelectionsNew();
    },
  });

  $("#catSelector").on("change", function(e) {
    var Params = "?catid=" + $("#catSelector").val();
    $.ajax({
      url: basePath + "php/ajax/editOrderForm.php" + Params,
      success: function(response) {
        $("#categoryHolder").html(response);
        checkQuantitySelectionsNew();
      },
    });
  });

  // Handle category amount updates
  $("#categoryHolder").on("change", ".CQty", function() {
    var Params  = "?CID=" + $("#catSelector").val();
        Params += "&familyType=" + $(this).attr("id");
        Params += "&cQty=" + $(this).val();
    $.ajax({
      url: basePath + "php/ajax/setOrderForm.php" + Params,
      success: function(response) {
        $("#msgLog").html(response);
        $("#msgLog").stop(true,true).show().delay(3000).hide(300);
        checkQuantitySelectionsNew();
      },
    });
  });

  // Handle item quantity updates
  $("#categoryHolder").on("change", ".IQty", function() {
    var Params  = "?itemID=" + $(this).attr("id").substring(1);
        Params += "&familyType=" + familyTypeExtractor($(this).attr("id").substring(0,1));
        Params += "&newQty=" + $(this).val();
    $.ajax({
      url: basePath + "php/ajax/setOrderForm.php" + Params,
      success: function(response) {
        $("#msgLog").html(response);
        $("#msgLog").stop(true,true).show().delay(3000).hide(300);
        checkQuantitySelectionsNew();
      },
    });
  });

  function appendError(err, append) {
    if (err != '') {
      return( err + "<br>" + append)
    }
    return (append);
  }

  function checkQuantitySelectionsNew() {
    var maxLarge  = Number($("#large").val());
    var maxMedium = Number($("#medium").val());
    var maxSmall  = Number($("#small").val());
    var errorMsg  = '';
    // Get the count of each category
    // Large items
    var largeItems = $(".IQty[id^=l]");
    var largeCount = 0;
    for (i = 0; i < largeItems.length; i++) {
      var currVal = Number($(largeItems[i]).val());
      if (currVal > maxLarge) {
        errorMsg = appendError(errorMsg, "An item's quantity in the large family selection is greater than the large category quantity.");
      }
      largeCount += currVal;
    }
    if ( largeCount < maxLarge ) {
      errorMsg = appendError(errorMsg, "There are not enough items to match the large selection quantity.");
    }
    // Medium items
    var medItems = $(".IQty[id^=m]");
    var medCount = 0;
    for (i = 0; i < medItems.length; i++) {
      var currVal = Number($(medItems[i]).val());
      if (currVal > maxMedium) {
        errorMsg = appendError(errorMsg, "An item's quantity in the medium family selection is greater than the medium category quantity.");
      }
      medCount += currVal;
    }
    if ( medCount < maxMedium ) {
      errorMsg = appendError(errorMsg, "There are not enough items to match the medium selection quantity.");
    }
    // Small items
    var smallItems = $(".IQty[id^=s]");
    var smallCount = 0;
    for (i = 0; i < smallItems.length; i++) {
      var currVal = Number($(smallItems[i]).val());
      if (currVal > maxSmall) {
        //$(this);
        errorMsg = appendError(errorMsg, "An item's quantity in the small family selection is greater than the small category quantity.");
      }
      smallCount += currVal;
    }
    if ( smallCount < maxSmall ) {
      errorMsg = appendError(errorMsg, "There are not enough items to match the small selection quantity.");
    }

    if (errorMsg != '') {
      $("#errMsgs").html(errorMsg).show(300);
    }
    else {
      $("#errMsgs").hide();
    }
  }


</script>
