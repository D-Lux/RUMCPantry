<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

	<h3>Order Form: Category Ordering</h3>



	<div class="body-content">

    <table class="table table-striped" id="iCatOrderTable">
      <thead class="thead-dark">
        <th>Order</th>
        <th>Category Name</th>
        <th>Active Items</th>
        <th></th>
      </thead>
      <tbody></tbody>
    </table>
  </div>


<?php include 'php/footer.php'; ?>


<script type="text/javascript">
	function buildCatTable() {
    $('#iCatOrderTable').DataTable({
      "ordering"      : false,
      "searching"     : false,
      "ajax": {
          "url"       : "php/ajax/categoryOrderList.php",
      },
    });
  }

  $("#iCatOrderTable").on("click", ".btn-up", function(e) {
    e.preventDefault();
    var ord = $(this).attr("id").substring(2);
    $.ajax({
      url      : 'php/ajax/adjustCatOrder.php?up=1&ord=' + ord,
      success  : function(data) {
        buildCatTable();
      },
    });
  });

  $("#iCatOrderTable").on("click", ".btn-down", function(e) {
    e.preventDefault();
    var ord = $(this).attr("id").substring(2);
    $.ajax({
      url      : 'php/ajax/adjustCatOrder.php?down=1&ord=' + ord,
      success  : function(data) {
        buildCatTable();
      },
    });
  });

  buildCatTable();

</script>