function validateNewAppts() {
	var errors = 0;
	var response = "";
	
	// Check to verify all times are unique
	var formSlots = document.querySelectorAll("#AppointmentForm input[name='time[]']");
	var numSlots = formSlots.length;
	var timeSlots = [];

	// Make sure we have at least one time slot, otherwise add an error message
	if (numSlots > 0) {
		for (i = 0; i < numSlots; i++) {
			// If the time slot isn't in the saved array, store it
			if (timeSlots.indexOf(formSlots[i].value) === -1) {
				timeSlots.push(formSlots[i].value);
			}
			// If there is a duplicate time slot, we need to save the error
			else {
				errors++;
				response += "Cannot have duplicate time slots (" + formSlots[i].value + ")\n";
				break;
			}
		}
	}
	else {
		errors++;
		response += "Must have at least one time slot\n";
	}
	
	if (errors > 0) {
		alert("The form contains (" + errors + ") error(s).\n" + response);
		return false;
	}
	else {
		return true;
	}
}

function deleteTimeTableRow(row)	{
	document.getElementById('timeTable').deleteRow(row.parentNode.parentNode.rowIndex);
}

function addTimeSlot() {
	// Get a reference to the table
	var table = document.getElementById("timeTable");
	
	// Create an empty <tr> element and add it to the end of the table
	var row = table.insertRow(-1);
	
	// Insert new cells
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);

	// Add default information to these cells:
	cell1.innerHTML = "<input type='time' name='time[]' value='10:00' step='900'>";
	cell2.innerHTML = "<input type='number' name='qty[]' value='6' min='1' max='10'>";
	cell3.innerHTML = "<input class='btn_trash' type='button' value=' ' onclick='deleteTimeTableRow(this)'>";
}

function AJAX_SetAppointment(callingSlot) {
	// Gets the setID number so we can pull information needed from the page
	var IDNum = callingSlot.id.substring(7);
	var invoiceIDNum = document.getElementById("InvoiceID" + IDNum).value;
	var clientName = document.getElementById("Clients" + IDNum).value;
	
	// Break apart the client name into first and last names
	var lastNameStartChar = clientName.search(",");
	var clientLastName = "Available";
	var clientFirstName = "Available";
	if (lastNameStartChar > 0) {
		var clientLastName = clientName.substring(0, lastNameStartChar);
		var clientFirstName = clientName.substring(lastNameStartChar + 2);
	}

	// Run the AJAX stuff
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// Generate the IDs for the fields we will be changing
	var famSizeIDTag = "famSize" + IDNum;
	var phoneNoIDTag = "phoneNo" + IDNum;
	var statusIDTag = "status" + IDNum;
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			
			var responseData = this.responseText;
			var findPhoneBreak = responseData.search("!PHONENO!");
			var findStatusBreak = responseData.search("!STATUS!");	// TODO: Make this cleaner somehow?		
			
			// Defaults
			var familyData ="<td>0</td>";
			var statusData = "<td>Unassigned</td>";
			var phoneData = "<td>-</td>";
			
			if (findPhoneBreak > 0) {
				familyData = responseData.substring(0, findPhoneBreak);
				if (findStatusBreak > 0) {
					phoneData = responseData.substring(findPhoneBreak + 9, findStatusBreak);
					statusData = responseData.substring(findStatusBreak + 8);
				}
			}
			
			document.getElementById(famSizeIDTag).innerHTML = familyData;
			document.getElementById(phoneNoIDTag).innerHTML = phoneData;
			document.getElementById(statusIDTag).innerHTML = statusData;
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setAppointmentClient.php?" +
				 "fName=" + clientFirstName + "&" +
				 "lName=" + clientLastName + "&" +
				 "invoiceID=" + invoiceIDNum ,true);
	xmlhttp.send();
}

function AJAX_ActivateAppointment(callingSlot) {
	// Gets the setID number so we can pull information needed from the page

	var IDNum = callingSlot.id.substring(4);
	var statusIDTag = document.getElementById("status" + IDNum);
	var invoiceIDNum = document.getElementById("InvoiceID" + IDNum).value;
	// Run the AJAX stuff
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// Generate the IDs for the fields we will be changing
	var statusIDTag = "status" + IDNum;
	
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var responseData = this.responseText;
			document.getElementById(statusIDTag).innerHTML = responseData;
			
		}
	};
	xmlhttp.open("GET","/RUMCPantry/php/ajax/setAppointmentClient.php?" +
				 "activate=1&" +
				 "invoiceID=" + invoiceIDNum ,true);
	xmlhttp.send();
}