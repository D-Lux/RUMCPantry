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
	var memberFirstName = document.forms["addMember"]["memberFirstName"].value;
    var memberLastName = document.forms["addMember"]["memberLastName"].value;
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

function viewTab(evt, tabID) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabID.id).style.display = "block";
    evt.currentTarget.className += " active";
}