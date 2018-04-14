/*
* © 2018 Daniel Luxa ALL RIGHTS RESERVED
*/

function updateHiddenClientID() {
	var input = document.getElementById('clientID');
	list = input.getAttribute('list');
	options = document.querySelectorAll('#' + list + ' option');
	hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');
	label = input.value;

	hiddenInput.value = label;

	for(var i = 0; i < options.length; i++) {
		var option = options[i];
		if(option.innerText === label) {
			hiddenInput.value = option.getAttribute('data-value');
			break;
		}
	}
}

function toggleOption(ele) {
	// Find the option appropriate to our button and show the options
	var optionSelected = document.getElementById(ele.name);
	optionSelected.style.display = "block";
	
	// Hide the option buttons
	document.getElementById("awc_options").style.display = "none";
}


$( document ).ready(function() {
    $("#existingForm").on('submit', function( e ) {
      e.preventDefault();
      $.ajax({
        url      : 'php/ajax/ajax_WalkInChecker.php',
        dataType : 'json',
        type     : 'POST',
        data     : { field1: $("#clientID-hidden").val() },
        success  : function(data) {
          // If we're getting redirected to a new page, go there
          if (data.status == 1) {
            $('#existingForm').off('submit').submit();
            //$(this).submit();
          }
          else if (data.status == 2) {
            alert("Client already has an appointment today.");
          }
          else {
            alert("Please select a valid client.");
          }     
        },
        error    : function() {
          alert("There was a connection error, reload and try again.");
        }
      });
      
    });
});


function validateNewWalkIn() {
	
    var response        = "";
    var clientFirstName = $("[name='clientFirstName']").val();
    var clientLastName  = $("[name='clientLastName']").val();
    var birthDate  		  = $("[name='birthDate']").val();
    var errors = 0;

	if (clientFirstName == "" || clientFirstName == null || clientFirstName.length == 0 ) {
        errors++;
        response += "First Name field is empty. \n";
    }
	if (clientLastName == "" || clientLastName.length == 0 || clientLastName == null) {
        errors++;
        response += "Last Name field is empty. \n";
    }
	if (birthDate == "" || birthDate == null || birthDate.length == 0 ) {
        errors++;
        response += "Date of Birth is not set. \n";
    }
    if (errors > 0) {
        alert("There are " + errors + " errors in the form. \nPlease fix and resubmit. \nThe errors are: \n" + response);
		return false;
    }
	else {
		return true;
	}
	
}