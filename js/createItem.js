function validateItemAdd() {

    var response    = "";
	var category    = $("[name='category']").val();
    var itemName    = $("[name='itemName']").val();
	var displayName = $("[name='displayName']").val();
	
    var errors = 0;


    if (category == "" || category.length == 0 || category == null) {
		$(".categoryField").css('color', 'red');
        errors++;
        response += "Category field is empty. \n"
    }
    if (itemName == "" || itemName.length == 0 || itemName == null) {
		$(".itemField").css('color', 'red');
        errors++;
        response += "Item name field is empty. \n"
    }
    if (displayName == "" || displayName.length == 0 || displayName == null) {
		$(".displayField").css('color', 'red');
        errors++;
        response += "Display name field is empty. \n"
    }

    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}

function validateCategoryAdd() {

	var name = $("#category").val();

    if (name == "" || name.length == 0 || name == null) {
        $(".nameField").css('color', 'red');
        alert("Must have a category name.");
		return false;
    }
	else {
        return true;
    }
}