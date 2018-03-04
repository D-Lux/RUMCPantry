<?php
  $pageRestriction = 10;
  include 'php/header.php';
  include 'php/backButton.php';
?>
<h3>End of day</h3>

<div class="body-content">
  <div id="endOfDayHolder"></div>
  
  
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  function buildEODPage() {
    $.ajax({
      url     : 'php/ajax/ajax_endOfDay.php',
      success : function(html) {
        $('#endOfDayHolder').html(html);
      }
    });
  }
  
  $("#endOfDayHolder").on("click", ".btn-no-show", function(e) {
    e.preventDefault();
    var invoiceID = $(this).val();
    $.ajax({
      url     : 'php/ajax/ajax_endOfDayResolve.php?status=<?=GetNoShowStatus()?>&id=' + invoiceID,
      success : function(html) {
        buildEODPage();
      }
    });
  });
  $("#endOfDayHolder").on("click", ".btn-documentation", function(e) {
    e.preventDefault();
    var invoiceID = $(this).val();
    $.ajax({
      url     : 'php/ajax/ajax_endOfDayResolve.php?status=<?=GetBadDocumentationStatus()?>&id=' + invoiceID,
      success : function(html) {
        buildEODPage();
      }
    });
  });
  $("#endOfDayHolder").on("click", ".btn-cancelled", function(e) {
    e.preventDefault();
    var invoiceID = $(this).val();
    $.ajax({
      url     : 'php/ajax/ajax_endOfDayResolve.php?status=<?=GetCanceledStatus()?>&id=' + invoiceID,
      success : function(html) {
        buildEODPage();
      }
    });
  });
  
  buildEODPage();
</script>