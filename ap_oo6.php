<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';

?>

<link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">

	<h3>Order Form: Category Ordering</h3>

 
	
	<div class="body-content">

    <table class="table table-inverse" id="iCatOrderTable">
      <thead>
        <th>Category Name</th>
        <th>Active Items</th>
        <th></th>
      </thead>
      <tbody></tbody>
    </table>
  </div>
	
	
<?php include 'php/footer.php'; ?>


<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	function buildCatTable() {
    $('#iCatOrderTable').DataTable({
      "info"          : true,
      "paging"        : true,
      "destroy"       : true,
      "searching"     : false,
      "processing"    : true,
      "serverSide"    : true,
      "orderClasses"  : false,
      "autoWidth"     : false,
      "ordering"      : false,
      "pagingType"    : "full_numbers",
      "ajax": {
          "url"       : "php/ajax/categoryOrderList.php",
      },
	  "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
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