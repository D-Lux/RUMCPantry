<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  include '../utilities.php';

  $name       = isset($_GET['n'])    ? fixInput($_GET['n']) : '';
  $password   = isset($_GET['pw'])   ? $_GET['pw']          : '';
  $permission = isset($_GET['perm']) ? $_GET['perm']        : '';
  $pID        = isset($_GET['id'])   ? $_GET['id']          : '';
  $ePW        = hashPassword($password);


  // Set up server connection
  $conn = connectDB();
  $returnArr = [];
	if ($conn->connect_error) {
    $returnArr['msg'] = "Database connection error, Login creation failed.";
    $returnArr['err'] = 1;
    die(json_encode($returnArr));
	}

  if (isset($_GET['create'])) {
    // Create insertion string
    $sql = "INSERT INTO permissions
          (login, permission_level, pw, locked)
          VALUES
          ('" . $name . "', " . $permission . ", '" . $ePW . "', 0)";

    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      $returnArr['msg'] = "New login created!";
      $returnArr['err'] = 0;
    }
    else {
      $returnArr['msg'] = "Insertion error, Login creation failed.";
      $returnArr['err'] = 1;
    }
    closeDB($conn);
    die(json_encode($returnArr));
  }
  else if (isset($_GET['dellogin'])) {
    // Create insertion string
    $sql = "DELETE FROM permissions
            WHERE permission_id = " . $pID;
    // Perform and test insertion
    if (queryDB($conn, $sql) === TRUE) {
      $returnArr['msg'] = "Login removed!";
      $returnArr['err'] = 0;
    }
    else {
      $returnArr['msg'] = "Deletion error.";
      $returnArr['err'] = 1;
    }
    closeDB($conn);
    die(json_encode($returnArr));
  }

  else if (isset($_GET['showEdit'])) {
    // Create insertion string
    $loginID = fixInput($_GET['id']);

    $sql = "SELECT login, locked
            FROM permissions
            WHERE permission_id = " . $loginID;


    $loginInfo = runQueryForOne($conn, $sql);


    $html = "<div>Edit Login: " . $loginInfo['login'] . "</div>
            <form id='updateForm' action=''>
              <input type='hidden' name='updateID' value=" . $loginID . ">

              <input name='updateLogin' id='updateLogin' class='newItemField' type='text' placeholder='Update Login'>

              <input name='updatePassword' id='updatePassword' class='newItemField' type='password' placeholder='Update Password'>" .

              ($loginInfo['locked'] == 1 ? '' : "
              <select name='newPermissions' id='newPermissions' class='newItemField'>
                <option value=-1>Select Permissions Level</option>
                <option value=" . PERM_BASE . ">Basic</option>
                <option value=" . PERM_RR   . ">Registration Level</option>
                <option value=" . PERM_MAX  . ">Admin Access</option>
              </select>") . "
              <button id='BTN_updateLogin' class='btn-nav' type='submit'><i class='fa fa-check'></i> Update Login</button>
            </form>";

    $data['html'] = $html;
    $data['err'] = 0;
    closeDB($conn);
    die(json_encode($data));
  }

  else if (isset($_POST['updateDetails'])) {
    $formData   = $_POST['updateDetails'];
    $loginID    = $formData[0]['value'];
    $newName    = fixInput($formData[1]['value']);
    $newPW      = hashPassword($formData[2]['value']);
    $newPerm    = isset($formData[3]) ? $formData[3]['value'] : -1;

    if (empty($newName) && empty($newPW) && $newPerm == -1) {
      $data['msg'] = "No changes were set.";
      $data['err']=1;
      die(json_encode($data));
    }
    $sets = [];
    if (!empty($newName)){
      $sets[] = " login = '" . $newName . "' ";
    }
    if (!empty($newPW)) {
      $sets[] = " pw = '" . $newPW . "' ";
    }
    if (!($newPerm == -1)) {
      $sets[] = " permission_level = " . $newPerm;
    }

    $sql = "UPDATE permissions
            SET " . implode(", ", $sets) . "
            WHERE permission_id = " . $loginID;

    if (queryDB($conn, $sql) === TRUE) {
      $data['msg'] = "Update successful";
      $data['err'] = 0;
    }
    else {
      $data['msg'] = "Update failed, try reloading.";
      $data['err'] = 2;
    }
    die(json_encode($data));

  }



?>