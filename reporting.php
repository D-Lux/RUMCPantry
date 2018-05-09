<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>
<link href="<?=$basePath?>includes/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css">
<link href="<?=$basePath?>includes/highcharts/css/highcharts.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/reporting.css">
<style>
.highcharts-credits {
  display: none !important;
}
.highcharts-container {
  overflow: hidden !important;
  height: auto !important;
}
</style>
<h3>Reporting</h3>

<div class='body-content'>
  <div class ="row">
    <div class="col-md-4">
      <input class="daterangepicker-field" value="01/01/2018 - 01/15/2018"/>
    </div>
  </div>
  <div class="clearfix"></div>
  <hr>

  <div id='reportData'></div>
<?php include 'php/footer.php'; ?>
<script type="text/javascript" src="includes/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="includes/highcharts/js/modules/drilldown.js"></script>
<script type="text/javascript" src="includes/highcharts/js/modules/exporting.js"></script>
<script type="text/javascript" src="includes/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="includes/daterangepicker/daterangepicker.js"></script>

<script type="text/javascript">
  var gStart = "<?=date('m/d/Y', strtotime('first day of last month'))?>";
  var gEnd = "<?=date('m/d/Y', strtotime('last day of last month'))?>";

// Updates the report page based on dates selected
  function getReport() {
    $.ajax({
      url: basePath + "php/ajax/updateReport.php?startDate=" + gStart + "&endDate=" + gEnd,
      success : function(data) {
        $("#reportData").html(data);
        $("#Overview").click();
      }
    });
  }

  // Handle the date range selector
  $(function() {
    $('.daterangepicker-field').daterangepicker({
      startDate: gStart,
      endDate: gEnd,
      showDropdowns: true,
      linkedCalendars: false,
      dateFormat: 'MMMM DD, YYYY',
      ranges: {
           'Today'      : [moment(), moment()],
           'Yesterday'  : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'This Month' : [moment().startOf('month'), moment().endOf('month')],
           'Last Month' : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'YTD'        : ['<?=date('m/d/Y', strtotime('first day of January ' . date('Y')))?>', '<?=date('m/d/Y', strtotime('today'))?>'],
        },
    }, function(start, end, label) {
      gStart = start.format('YYYY-MM-DD');
      gEnd = end.format('YYYY-MM-DD');
      getReport();
    });
  });

  // Run the report at the start
  getReport();

  // Handle opening and closing rows
  $(document).on("click", ".tablinks", function() {
    $(".tabcontent").removeClass("active");
    $(".tablinks").removeClass("activeButton");
    var openTab = "#" + this.id + "Content";
    $(openTab).addClass("active");
    $(this).addClass("activeButton");
  });

  $(document).on("click", "tr", function() {
    var toggleClass = "." + $(this).attr("id");
    $(toggleClass).toggle();
    $(this).find('i').toggleClass('down-caret');
  });

</script>