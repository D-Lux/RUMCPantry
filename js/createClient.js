function validateNewClient() {
	var response = "";
	var clientFirstName = document.getElementById("clientFNameField").value;
    var clientLastName = document.getElementById("clientLNameField").value;
    var errors = 0;
	
	if (clientFirstName == "" || clientFirstName.length == 0 || clientFirstName == null) {
        getElementAndColorIt("clientFirstName", "red");
        errors++;
        response += "First Name field is empty. \n";
    }
    if (clientLastName == "" || clientLastName.length == 0 || clientLastName == null) {
        getElementAndColorIt("clientLastName", "red");
        errors++;
        response += "Last Name field is empty. \n";
    }

    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}

function validateNewClientMember() {
    var response = "";
	var memberLastName = document.getElementById("memberFirstNameField").value;
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