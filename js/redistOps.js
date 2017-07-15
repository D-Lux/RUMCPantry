function validateNewPartner() {
	var response = "";
    var partnerLastName = document.getElementById("partnerNameField").value;
    var errors = 0;

    if (partnerLastName == "" || partnerLastName.length == 0 || partnerLastName == null) {
		getElementAndColorIt("partnerName", "red");
		alert("Partner must have a name");
        return false;
    } 
	else {
        return true;
    }
}