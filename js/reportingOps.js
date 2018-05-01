/*
* © 2018 Daniel Luxa ALL RIGHTS RESERVED
*/
/*

$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        showDropdowns: true,
        linkedCalendars: false,
        startDate: start,
        endDate: end,
        ranges: {
           'Today'      : [moment(), moment()],
           'Yesterday'  : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'This Month' : [moment().startOf('month'), moment().endOf('month')],
           'Last Month' : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});

  
// Updates the report page based on dates selected
function getReport() {
  $.ajax({
    url: basePath + "php/ajax/updateReport.php?startDate=" + $("#startDate").val() +
				 "&endDate=" + $("#endDate").val(),
    success : function(data) {
      $("#reportData").html(data);
    }
  });
}

getReport();
*/