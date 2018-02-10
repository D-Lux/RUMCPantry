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
        response += "pickup date field is empty. \n"
    }
    if (networkPartner == "" || networkPartner.length == 0 || networkPartner == null) {
        getElementAndColorIt("networkPartner", "red");
        errors++;
        response += "network partner field is empty. \n"
    }
    if (agency == "" || agency.length == 0 || agency == null) {
        getElementAndColorIt("agency", "red");
        errors++;
        response += "agency field is empty. \n"
    }
    if (donorName == "" || donorName.length == 0 || donorName == null) {
        getElementAndColorIt("donorName", "red");
        errors++;
        response += "donor name field is empty. \n"
    }
    if (city == "" || city.length == 0 || city == null) {
        getElementAndColorIt("city", "red");
        errors++;
        response += "city field is empty. \n"
    }


    if (frozenNonMeat < 0 || frozenNonMeat > 1000) {
        getElementAndColorIt("frozenNonMeat", "red");
        errors++;
        response += "frozen Non-Meat field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (frozenMeat < 0 || frozenMeat > 1000) {
        getElementAndColorIt("frozenMeat", "red");
        errors++;
        response += "frozen Meat field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (frozenPrepared < 0 || frozenPrepared > 1000) {
        getElementAndColorIt("frozenPrepared", "red");
        errors++;
        response += "frozen Prepared field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (refBakery < 0 || refBakery > 1000) {
        getElementAndColorIt("refBakery", "red");
        errors++;
        response += "ref Bakery field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (refProduce < 0 || refProduce > 1000) {
        getElementAndColorIt("refProduce", "red");
        errors++;
        response += "ref Produce field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (refDairyAndDeli < 0 || refDairyAndDeli > 1000) {
        getElementAndColorIt("refDairyAndDeli", "red");
        errors++;
        response += "ref Dairy And Deli field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (dryShelfStable < 0 || dryShelfStable > 1000) {
        getElementAndColorIt("dryShelfStable", "red");
        errors++;
        response += "dry Shelf Stable field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (dryNonFood < 0 || dryNonFood > 1000) {
        getElementAndColorIt("dryNonFood", "red");
        errors++;
        response += "dry Non-Food field contains a number that is less than 0 or greater than 1000. \n"
    }
    if (dryFoodDrive < 0 || dryFoodDrive > 1000) {
        getElementAndColorIt("dryFoodDrive", "red");
        errors++;
        response += "dry Food Drive field contains a number that is less than 0 or greater than 1000. \n"
    }



    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
        return false;
    } else {
        return true;
    }
}


$("#btn_createDonationPartner").on("click", function(e) {
  e.preventDefault();
  $("#warningMsgs").hide();
  var fieldData = $("#createDonationPartner").serialize().trim();
  $.ajax({
    url: "php/donationOps.php",
    data: fieldData,
    type: "POST",
    dataType: "json",
    context: document.body,
    success: function(msg) {
      if (msg.error == '') {
        var clearFields = $('input').not(':input[type=submit], :input[type=hidden]');
        $("#donationSuccess").html("Donation Partner Added!").show(300).delay(2000).hide(300);
        $(clearFields).val('');
        $("select").val('IL');
      }
      else {
        // something happened, show errors
        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
      }
    },
  });
});

$(".input-number").keypress(function(key) {
  var numLength = $(this).val().toString().length;
  var maxLength = 3;
  if ($(this).attr("id") == "iPhone2") {
    maxLength = 4;
  }
  if ($(this).attr("id") == "addressZipField") {
    maxLength = 5;
  }
  if (numLength > (maxLength - 1)) {
    return false;
  }
  if(key.charCode < 48 || key.charCode > 57) return false;
});
/*
  var response    = "";
  var errors      = 0;
  var name        = $("#iPartnerName").val();
  var city        = $("#iCity").val();
  var zip         = $("#iZip").val();
  var address     = $("#iAddress").val();
  var phoneNumber = $("#iPhone").val();

  if (name == "" || name.length == 0 || name == null) {
      errors++;
      response += "name field is empty. \n"
  }
  if (zip == "" || zip.length == 0 || zip == null) {
      errors++;
      response += "zip field is empty. \n"
  }
  if (address == "" || address.length == 0 || address == null) {
      errors++;
      response += "address field is empty. \n"
  }
  if (city == "" || city.length == 0 || city == null) {
      errors++;
      response += "city field is empty. \n"
  }
  if (phoneNumber == "" || phoneNumber.length == 0 || phoneNumber == null) {
      errors++;
      response += "phone number field is empty. \n"
  }


  if (errors > 0) {
      $("#").html("<pre>There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response + "</pre>");
      return false;
  } else {
      return true;
  }
}
*/