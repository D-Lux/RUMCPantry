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

$("#startDate, #endDate").on("change", function() { getReport(); });

getReport();