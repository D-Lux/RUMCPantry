function getElementAndColorIt(elementID, color) {
    var element = document.getElementById(elementID);
    element.style.color = color;
}


function validateNewClient() {

    var response = "";
	var clientFirstName = document.forms["addClient"]["clientFirsttName"].value;
    var clientLastName = document.forms["addClient"]["clientLastName"].value;
    var numAdults = document.forms["addClient"]["numAdults"].value;
    var errors = 0;


	if (clientFirstName == "" || clientFirstName.length == 0 || clientFirstName == null) {
        getElementAndColorIt("clientFirstName", "red");
        errors++;
        response += "First Name field is empty. \n"
    }
    if (clientLastName == "" || clientLastName.length == 0 || clientLastName == null) {
        getElementAndColorIt("clientLastName", "red");
        errors++;
        response += "Last Name field is empty. \n"
    }
    if (numAdults == "" || numAdults.length == 0 || numAdults == null || numAdults == "0") {
        getElementAndColorIt("numAdults", "red");
        errors++;
        response += "Clients must have at least one adult. \n"
    }

    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}

function validateNewClientMember() {
    var response = ""; // TODO
	var clientFirstName = document.forms["addClient"]["clientFirsttName"].value;
    var clientLastName = document.forms["addClient"]["clientLastName"].value;
    var errors = 0;


	if (clientFirstName == "" || clientFirstName.length == 0 || clientFirstName == null) {
        getElementAndColorIt("clientFirstName", "red");
        errors++;
        response += "First Name field is empty. \n"
    }
    if (clientLastName == "" || clientLastName.length == 0 || clientLastName == null) {
        getElementAndColorIt("clientLastName", "red");
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