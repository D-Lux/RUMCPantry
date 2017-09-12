// Updates the report page based on dates selected
function AJAX_UpdateReport() {
	var startDate = document.getElementById("startDate").value;
	var endDate = document.getElementById("endDate").value;

	// TODO: Date validation to make sure startDate is before endDate
	xmlhttp = newAJAXObj();
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("reportData").innerHTML = this.responseText;	
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/updateReport.php?" +
				 "startDate=" + startDate + "&" +
				 "endDate=" + endDate, true);
	xmlhttp.send();
}