/*
* © 2018 Daniel Luxa ALL RIGHTS RESERVED
*/

// For adding a new donation
$("#btn_newDonation").on("click", function(e) {
  e.preventDefault();
  $("#warningMsgs").stop().hide();
  $("#donationSuccess").stop(true, true).hide();
  var fieldData = $("#addDonation").serialize().trim();
  $.ajax({
    url: "php/donationOps.php",
    data: fieldData,
    type: "POST",
    dataType: "json",
    context: document.body,
    success: function(msg) {
      if (msg.error == '') {
        var clearFields = $('input').not('#iPickupDate, #iNetworkPartner, #iAgency');
        $("#donationSuccess").html("Donation Added!").show(250).delay(5000).hide(800);
        $(clearFields).val('');
      }
      else {
        // something happened, show errors
        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
      }
    },
  });
});

// For updating a donation
$("#btn_updateDonation").on("click", function(e) {
  e.preventDefault();
  $("#warningMsgs").stop().hide();
  $("#donationSuccess").stop(true, true).hide();
  var fieldData = $("#updateDonation").serialize().trim();
  $.ajax({
    url: "php/donationOps.php",
    data: fieldData,
    type: "POST",
    dataType: "json",
    context: document.body,
    success: function(msg) {
      if (msg.error == '') {
        $("#donationSuccess").html("Donation Updated!").show(250).delay(3000).hide(800);
      }
      else {
        // something happened, show errors
        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
      }
    },
  });
});

// For adding a new donation partner
$("#btn_createDonationPartner").on("click", function(e) {
  e.preventDefault();
  $("#warningMsgs").stop(true, true).hide();
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
        $("#donationSuccess").html("Donation Partner Added!").show(250).delay(5000).hide(800);
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

// For updating a donation partner
$("#btn_updateDonationPartner").on("click", function(e) {
  e.preventDefault();
  $("#warningMsgs").stop(true, true).hide();
  $("#donationSuccess").stop(true, true).hide();
  var fieldData = $("#updateDonationPartner").serialize().trim();
  $.ajax({
    url: "php/donationOps.php",
    data: fieldData,
    type: "POST",
    dataType: "json",
    context: document.body,
    success: function(msg) {
      if (msg.error == '') {
        $("#donationSuccess").html("Donation Partner Updated!").show(250).delay(5000).hide(800);
      }
      else {
        $("#warningMsgs").html("<pre>" + msg.error + "</pre>").show(300);
      }
    },
  });
});

$(".chosen-select").chosen();