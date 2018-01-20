<?php 
$pageRestriction = -1;
include 'php/header.php';
?>
	
	<style>
	p {
		color : red;
	}
	
	</style>
	<h3>Welcome</h3>
	
	<div class="body_content">		
		<form action="php/mainpage.php">
      <div class="form-row">
        <div class="form-group col-md-6">
          <input id="loginTextBox" type="text" name="login" placeholder="Log In" >
          <div style="display:none;margin:0px;" id="loginMsgs"></div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <input id="passwordTextBox" type="password" name="password" placeholder="Password" autocomplete="off">
          <div style="display:none;margin:0px;" id="passwordMsgs"></div>
        </div>
      </div>
		  <input id="submitLogin" class='btn-nav' type="submit" value="Submit">
		</form>
    <div id="debugmsgs"></div>
	
<?php include 'php/footer.php'; ?>

<script type="text/javascript">
  $(document).ready(function(){
      $('#loginTextBox').on('focus', function() {
        $('#loginMsgs').hide(200);
      });
      $('#passwordTextBox').on('focus', function() {
        $('#passwordMsgs').hide(200);
      });
      $('#submitLogin').on('click', function (e) {
        e.preventDefault();
        $.ajax({
          url      : 'php/ajax/handleLogin.php',
          dataType : 'json',
          type     : 'POST',
          data     : { field1: $('#loginTextBox').val(), field2: $('#passwordTextBox').val(), },
          success  : function(data) {
            if (data.err == 1) {
              $("#loginMsgs").html(data.msg).show(300);
            }
            else if (data.err == 2) {
              $("#passwordMsgs").html(data.msg).show(300);
              $("#passwordTextBox").val("");
            }
            else {
              $("#debugmsgs").html("Permissions given: " + data.perm);
              switch(data.perm) { 
                //case 99:
                //  window.location.assign("MainPage.php");
                //  break;
                ///case 10:
                //  window.location.assign("MainPage.php");
                //  break;
                case 1:
                default:
                  //window.location.assign("MainPage.php");
              }
              
            }
          },
        });
      });
	});
  
  if (getCookie("clientApptSet") != "") {
    window.alert("Appointment set! Thank you, see you next time!");
    removeCookie("clientApptSet");
  }
  if (getCookie("clientSkippedAppt") != "") {
    window.alert("Thank you for your order, please give us a call to set up another appointment!");
    removeCookie("clientSkippedAppt");
  }		
</script>