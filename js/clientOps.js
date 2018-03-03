
function validateUpdatedClient() {

    var numAdults = document.forms["updateClient"]["numAdults"].value;

    if (numAdults == "" || numAdults.length == 0 || numAdults == null || numAdults == "0") {
        getElementAndColorIt("numAdults", "red");
        alert("Clients must have at least one adult. \nPlease fix and resubmit.");
    }
	else {
        return true;
    }
}

function validateNewClientMember() {
    var response = "";
	var memberFirstName = document.getElementById("memberFirstNameField").value;
    var memberLastName = document.getElementById("memberLastNameField").value;
    var errors = 0;


	if (memberFirstName == "" || memberFirstName.length == 0 || memberFirstName == null) {
        getElementAndColorIt("memberFirstName", "red");
        errors++;
        response += "First Name field is empty. \n"
    }
    if (memberLastName == "" || memberLastName.length == 0 || memberLastName == null) {
        getElementAndColorIt("memberLastName", "red");
        errors++;
        response += "Last Name field is empty. \n"
    }

    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}