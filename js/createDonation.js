function getElementAndColorIt(elementID, color) {
    var element = document.getElementById(elementID);
    element.style.color = color;
}



function validateDonationAdd() {

    var response = "";
    var pickupDate = document.forms["addDonation"]["pickupDate"].value;
    var networkPartner = document.forms["addDonation"]["networkPartner"].value;
    var agency = document.forms["addDonation"]["agency"].value;
    var donorName = document.forms["addDonation"]["donorName"].value;
    var city = document.forms["addDonation"]["city"].value;
    var frozenNonMeat = document.forms["addDonation"]["frozenNonMeat"].value;
    var frozenMeat = document.forms["addDonation"]["frozenMeat"].value;
    var frozenPrepared = document.forms["addDonation"]["frozenPrepared"].value;
    var refBakery = document.forms["addDonation"]["refBakery"].value;
    var refProduce = document.forms["addDonation"]["refProduce"].value;
    var refDairyAndDeli = document.forms["addDonation"]["refDairyAndDeli"].value;
    var dryShelfStable = document.forms["addDonation"]["dryShelfStable"].value;
    var dryNonFood = document.forms["addDonation"]["dryNonFood"].value;
    var dryFoodDrive = document.forms["addDonation"]["dryFoodDrive"].value;

    var errors = 0;



    if (pickupDate == "" || pickupDate.length == 0 || pickupDate == null) {
        getElementAndColorIt("pickupDate", "red");
        errors++;
        response += "pickupDate field is empty. \n"
    }
    if (networkPartner == "" || networkPartner.length == 0 || networkPartner == null) {
        getElementAndColorIt("networkPartner", "red");
        errors++;
        response += "networkPartner field is empty. \n"
    }
    if (agency == "" || agency.length == 0 || agency == null) {
        getElementAndColorIt("agency", "red");
        errors++;
        response += "agency field is empty. \n"
    }
    if (donorName == "" || donorName.length == 0 || donorName == null) {
        getElementAndColorIt("donorName", "red");
        errors++;
        response += "donorName field is empty. \n"
    }
    if (city == "" || city.length == 0 || city == null) {
        getElementAndColorIt("city", "red");
        errors++;
        response += "city field is empty. \n"
    }


    if (frozenNonMeat < 0 || frozenNonMeat > 100000) {
        getElementAndColorIt("frozenNonMeat", "red");
        errors++;
        response += "frozenNonMeat field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (frozenMeat < 0 || frozenMeat > 100000) {
        getElementAndColorIt("frozenMeat", "red");
        errors++;
        response += "frozenMeat field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (frozenPrepared < 0 || frozenPrepared > 100000) {
        getElementAndColorIt("frozenPrepared", "red");
        errors++;
        response += "frozenPrepared field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (refBakery < 0 || refBakery > 100000) {
        getElementAndColorIt("refBakery", "red");
        errors++;
        response += "refBakery field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (refProduce < 0 || refProduce > 100000) {
        getElementAndColorIt("refProduce", "red");
        errors++;
        response += "refProduce field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (refDairyAndDeli < 0 || refDairyAndDeli > 100000) {
        getElementAndColorIt("refDairyAndDeli", "red");
        errors++;
        response += "refDairyAndDeli field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (dryShelfStable < 0 || dryShelfStable > 100000) {
        getElementAndColorIt("dryShelfStable", "red");
        errors++;
        response += "dryShelfStable field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (dryNonFood < 0 || dryNonFood > 100000) {
        getElementAndColorIt("dryNonFood", "red");
        errors++;
        response += "dryNonFood field contains a number that is less than 0 or greater than 100000. \n"
    }
    if (dryFoodDrive < 0 || dryFoodDrive > 100000) {
        getElementAndColorIt("dryFoodDrive", "red");
        errors++;
        response += "dryFoodDrive field contains a number that is less than 0 or greater than 100000. \n"
    }



    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}

function validateDonationPartnerAdd() {
    var response = "";
    var name = document.forms["addDonationPartner"]["name"].value;
    var zip = document.forms["addDonationPartner"]["zip"].value;
    var address = document.forms["addDonationPartner"]["address"].value;
    var city = document.forms["addDonationPartner"]["city"].value;
    var phoneNumber = document.forms["addDonationPartner"]["phoneNumber"].value;


    var errors = 0;


    if (name == "" || name.length == 0 || name == null) {
        getElementAndColorIt("name", "red");
        errors++;
        response += "name field is empty. \n"
    }
    if (zip == "" || zip.length == 0 || zip == null) {
        getElementAndColorIt("zip", "red");
        errors++;
        response += "zip field is empty. \n"
    }
    if (address == "" || address.length == 0 || address == null) {
        getElementAndColorIt("address", "red");
        errors++;
        response += "address field is empty. \n"
    }
    if (city == "" || city.length == 0 || city == null) {
        getElementAndColorIt("city", "red");
        errors++;
        response += "city field is empty. \n"
    }
    if (phoneNumber == "" || phoneNumber.length == 0 || phoneNumber == null) {
        getElementAndColorIt("phoneNumber", "red");
        errors++;
        response += "phoneNumber field is empty. \n"
    }





    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}