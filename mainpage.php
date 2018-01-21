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
	
	<div class="body-content">		
		<form action="php/mainpage.php">
      <div id="permissionMsgs" style="display:none;"></div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <input id="loginTextBox" type="text" name="login" placeholder="Log In Name" >
          <div style="display:none;margin:0px;" id="loginMsgs"></div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <input id="passwordTextBox" type="password" name="password" placeholder="Password" autocomplete="off">
          <div style="display:none;margin:0px;" id="passwordMsgs"></div>
        </div>
      </div>
		  <button id="submitLogin" class='btn-nav' type="submit"><i class="fa fa-sign-in"></i> Log In</button>
		</form>
    
	
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
            switch(parseInt(data.perm)) { 
              case 99:
              case 10:
                window.location.assign("ap1.php");
                break;
              case 1:
              default:
                window.location.assign("cp1.php");
            }
            
          }
        },
      });
    });
	});
  if (getCookie("badRestrictions") != "") {
    $("#permissionMsgs").html("<p>The page you attempted to access had bad permissions</p>").show();
    removeCookie("badRestrictions");
	}
  if (getCookie("noPermission") != "") {
		$("#permissionMsgs").html("<p>You do not have permission to view that page, please log in again</p>").show();
		removeCookie("noPermission");
	}
</script>