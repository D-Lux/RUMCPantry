<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

<div id="clickOut" style="display:none;"></div>

	<style>
	p {
		color : red;
	}
  #loginMsgs {
    top: 25%;
    left: 50%;
    width: 100%;
    height: 40px;
    pointer-events: none;
  }
  td {
    vertical-align: middle;
  }
  .ebtn:hover {
    color: white !important;
  }
  #editBox {
    position: absolute;
    z-index: 40;
    padding-top: 3%;
    background-color: rgb(241, 252, 212);
    margin-left:30%;
    width: 350px;
    height: 300px;
    border-style: solid;
    text-align: center;
  }
  #clickOut {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 20;
    width: 100%;
    height: 100%;
    background-color: rgba(38, 12, 12, 0.50);
  }
	</style>
	<h3>Adjust Logins</h3>

	<div class="body-content">

    <div id="editBox" style="display:none;"></div>
		<div id="loginMsgs" class="hoverMsg" style="display:none;"></div>
    <table class="table" id="loginTable">
      <thead>
        <tr>
          <th>Login</th>
          <th>Permission</th>
          <th></th>
        </tr>
      </thead>
    </table>
    <div style="display:none;padding-top:50px;" id="newFields">
      <form action="">
        <div class="row">
          <div class="col-sm-4">
            <input name="newLogin" id="newLogin" class="newItemField" type="text" placeholder="New Login Name">
          </div>
        </div>
        <div class="row" style="display:none;" id="newLoginMsgs"></div>
        <div class="row">
          <div class="col-sm-4">
            <input name="newPassword" id="newPassword" class="newItemField" type="password" placeholder="Password">
          </div>
        </div>
        <div class="row" style="display:none;" id="newPasswordMsgs"></div>
        <div class="row">
          <div class="col-sm-4">
            <select name="newPermissions" id="newPermissions" class="newItemField">
              <option value=-1>Select Permissions Level</option>
              <option value=<?=PERM_BASE?>>Basic</option>
              <option value=<?=PERM_RR?>>Registration Level</option>
              <option value=<?=PERM_MAX?>>Admin Access</option>
            </select>
          </div>
        </div>
        <div class="row" style="display:none;" id="newPermissionsMsgs"></div>
        <div class="row">
          <div class="col-sm-4">
            <button id="BTN_createNewLogin" class='btn-nav' type="submit"><i class="fa fa-check"></i> Add Login</button>
          </div>
        </div>
      </form>
    </div>
    <button id="BTN_newLogin" class='btn-nav' type="submit"><i class="fa fa-user"></i> New Login</button>



<?php include 'php/footer.php'; ?>
<script type="text/javascript">

  (function($){
    $.isBlank = function(obj){
      return(!obj || $.trim(obj) === "");
    };
  })(jQuery);

  function drawLoginTable() {
    $('#loginTable').DataTable({
      "searching"     : false,
      "ordering"      : false,
      "ajax": {
          "url"       : "php/ajax/loginList.php",
      },
    });
    $('#example-problem').on('click', '.btn-details', function(){
     showModalDialog(this);
  });

    $("#loginTable").off("click", ".btn_dlt").on("click", ".btn_dlt", function() {
      permID = $(this).val();
      var result = confirm("Do you want to remove this login?");
      if (result) {
        $.ajax({
          url      : 'php/ajax/adjustLogin.php?dellogin=1&id=' + permID,
          dataType : 'json',
          success  : function(data) {
            $("#loginMsgs").stop(true,true).hide().html(data.msg).show(250).delay(5000).hide(800);
            if (data.err == 0) {
              drawLoginTable()
            }
          },
        });
      }
    });
    $("#loginTable").off("click", ".ebtn").on("click", ".ebtn", function() {
      permID = $(this).val();
      $.ajax({
        url      : 'php/ajax/adjustLogin.php?showEdit=1&id=' + permID,
        dataType : 'json',
        success  : function(data) {
          if (data.err == 0) {
            $("#editBox").html(data.html);
            $("#clickOut").fadeIn(300);
            $("#editBox").show(300);
            $("#editBox").off("click", "#BTN_updateLogin").on("click", "#BTN_updateLogin", function(e) {
            //$("#updateForm").off("submit").on( "submit", function( e ) {
              e.preventDefault();
              var updateDetails = $('#updateForm').serializeArray();
              $.ajax({
                type     : 'POST',
                url      : 'php/ajax/adjustLogin.php',
                dataType : 'json',
                data     : {
                  updateDetails,
                },
                success  : function(updateData) {
                  $("#clickOut").click();
                  $("#loginMsgs").stop(true,true).hide().html(updateData.msg).show(250).delay(5000).hide(800);
                  drawLoginTable();
                },
              });
            });
          }
        },
      });
    });
  }
  $(document).ready(function(){
    $(".newItemField").on("focus", function() {
      var msgBlock = "#" + $(this).attr("id") + "Msgs";
      $(msgBlock).hide(200);
    });
    $("#BTN_newLogin").on("click", function (e) {
      e.preventDefault();
      $("#BTN_newLogin").hide(250);
      $("#newFields").show(350);
    });
    $("#BTN_createNewLogin").on("click", function(e) {
      e.preventDefault();
      var newName = $("#newLogin").val();
      var newPW   = $("#newPassword").val();
      var newPerm = $("#newPermissions").val();

      var errors = 0;
      //if (newName == null || newName == " " || newName == "") {
      if ($.isBlank(newName)) {
        $("#newLoginMsgs").html("<p>Must add a non-blank name</p>").show(250);
        errors++;
      }
      if ($.isBlank(newPW)) {
        $("#newPasswordMsgs").html("<p>Must add a non-blank password</p>").show(250);
        errors++;
      }
      if (newPerm <= 0) {
        $("#newPermissionsMsgs").html("<p>Must select a valid permission level</p>").show(250);
        errors++;
      }

      if (errors <= 0) {
        $("#BTN_newLogin").show(250);
        $("#newFields").hide(350);

        $.ajax({
          url      : 'php/ajax/adjustLogin.php?create=1&n=' + newName + '&pw=' + newPW + '&perm=' + newPerm,
          dataType : 'json',
          success  : function(data) {
            $("#loginMsgs").stop(true,true).hide().html(data.msg).show(250).delay(5000).hide(800);
            if (data.err == 0) {
              $("#newPermissions").val(-1);
              $("#newPassword").val('');
              $("#newLogin").val('');
              drawLoginTable()
            }
          },
        });
      }

    });
    $("#clickOut").on("click", function() {
      $("#clickOut").fadeOut(300);
      $("#editBox").hide(300);
    });
    drawLoginTable();

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