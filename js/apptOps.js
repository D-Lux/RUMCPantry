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

function deleteTimeTableRow(row){
	if (confirm("Are you sure you want to remove this time slot?")) {
		document.getElementById('timeTable').deleteRow(row.parentNode.parentNode.rowIndex);
	}
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
	cell2.innerHTML = "<input type='text' style='width:90px;' maxlength=3 name='qty[]' value='5' min='1' >";
	cell3.innerHTML = "<a class='rm_row btn-icon' href='#' ><i class='fa fa-trash'></i></a>";
	
	// Re-add the click events
	$('.rm_row').off("click").on("click", function(e) {
		e.stopPropagation();
		e.preventDefault();
		deleteTimeTableRow(this);
	});
}