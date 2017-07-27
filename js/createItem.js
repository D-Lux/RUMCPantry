function validateItemAdd() {

    var response = "";
    var category = document.forms["addItem"]["category"].value;
    var itemName = document.forms["addItem"]["itemName"].value;
    var displayName = document.forms["addItem"]["displayName"].value;
    var price = document.forms["addItem"]["price"].value;
    var errors = 0;


    if (category == "" || category.length == 0 || category == null) {
        getElementAndColorIt("category", "red");
        errors++;
        response += "Category field is empty. \n"
    }
    if (itemName == "" || itemName.length == 0 || itemName == null) {
        getElementAndColorIt("itemName", "red");
        errors++;
        response += "Item name field is empty. \n"
    }
    if (displayName == "" || displayName.length == 0 || displayName == null) {
        getElementAndColorIt("displayName", "red");
        errors++;
        response += "Display name field is empty. \n"
    }
    if (price == "" || price.length == 0 || price == null) {
        getElementAndColorIt("price", "red");
        errors++;
        response += "price field is empty. \n"
    }
    if (price < 0 || price > 10000) {
        getElementAndColorIt("price", "red");
        errors++;
        response += "price field contains a number that is less than 0 or greater than 10000. \n"
    }


    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}

function validateCategoryAdd() {

    var response = "";
    var name = document.forms["addCategory"]["name"].value;
    var errors = 0;



    if (name == "" || name.length == 0 || name == null) {
        getElementAndColorIt("name", "red");
        errors++;
        response += "name field is empty. \n"
    }


    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}