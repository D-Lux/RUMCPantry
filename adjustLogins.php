<div id="clickOut" class="clickOutMsg" style="display:none;"></div>
<?php
$pageRestriction = -1; //99;
include 'php/header.php';
include 'php/backButton.php';
?>

  <link rel="stylesheet" type="text/css" href="includes/jquery.dataTables.min.css">
	<style>
	p {
		color : red;
	}
  #loginMsgs {
    top: 25%;
    left: 50%;
    width: 100%;
    height: 40px;
  }
  td {
    vertical-align: middle;
  }
  .editMsg {
    position: absolute;
    z-index: 20;
    padding-top: 20%;
    margin-top: -15%;
    text-align: center;
    font-size: 1.2em;
    background-color: rgb(241, 252, 212);
    font-weight:bold;
    margin-left:150px;
    width: 500px;
    border-style: solid;
  }
  .clickOutMsg {
    position: absolute;
    z-index: 10;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 1.2em;
    background-color: rgba(38, 12, 12, 0.50);
    font-weight:bold;
    
    width: 800px;
  }
	</style>
	<h3>Adjust Logins</h3>
  
	<div class="body-content">
    
    <div id="editBox" class="editMsg" style="display:none;">Test</div>
		<div id="loginMsgs" class="hoverMsg" style="display:none;"></div>
    <table class="table" id="loginTable">
      <thead>
        <tr>
          <th>Login</th>
          <th>Permission</th>
          <th>Edit</th>
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
              <option value=<?=PERM_BASE?>>Basic</option><option value=<?=PERM_RR?>>Registration Level</option><option value=<?=PERM_MAX?>>Admin Access</option>
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
<script type="text/javascript" charset="utf8" src="includes/jquery.dataTables.min.js"></script>
<script type="text/javascript">

  (function($){
    $.isBlank = function(obj){
      return(!obj || $.trim(obj) === "");
    };
  })(jQuery);
  
  function drawLoginTable() {
    $('#loginTable').DataTable({
      "info"          : false,
      "paging"        : false,
      "destroy"       : true,
      "searching"     : false,
      "processing"    : true,
      "serverSide"    : true,
      "orderClasses"  : false,
      "autoWidth"     : false,
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
            $("#loginMsgs").html(data.msg).show(250).delay(5000).hide(800);
            if (data.err == 0) {
              drawLoginTable()
            }
          },
        });
      }
    });
    $("#loginTable").off("click", ".btn-edit").on("click", ".btn-edit", function() {   
      permID = $(this).val();
      if (false) {
        $.ajax({
          url      : 'php/ajax/adjustLogin.php?showEdit=1&id=' + permID,
          dataType : 'json',
          success  : function(data) {
            $("#editBox").html(data.html);
            $("#clickOut").fadeIn(300);
            $("#editBox").show(300);
          },
        });
      }
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
            $("#loginMsgs").html(data.msg).show(250).delay(5000).hide(800);
            if (data.err == 0) {
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