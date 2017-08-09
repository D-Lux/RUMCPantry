function validateNewPartner() {
    var partnerLastName = document.getElementById("partnerNameField").value;;

    if (partnerLastName == "" || partnerLastName.length == 0 || partnerLastName == null) {
		getElementAndColorIt("partnerName", "red");
		alert("Partner must have a name");
        return false;
    } 
	else {
        return true;
    }
}

function validateNewRedistItem() {
    var itemName = document.getElementById("itemNameField").value;

    if (itemName == "" || itemName.length == 0 || itemName == null) {
		getElementAndColorIt("itemName", "red");
		alert("Item must have a name");
        return false;
    } 
	else {
        return true;
    }
}