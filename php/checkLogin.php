<?php


if( !isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60 ) 
  $_SESSION['last_access'] = time(); 

if (!isset($pageRestriction)) {
  //header ("location: /RUMCPantry/login.php?err=2");
}

if ($pageRestriction > -1) {
  //$_SESSION['perms']
  // stuff
}


/*
echo "<br>";
      //$pw = 
      //echo $pw;
      echo "<br>" . $result;

      //store logins md5,
      //store passwords using above

      /*
      session_start(); (search for and remove, add to include.php)
      $_SESSION['permissions'];
      session_destroy();
      unset($_SESSION['permissions']);


      $sql = "SELECT login, password, permissions
              FROM logins"
      $results = queryDB($conn, $sql);
      $loginFound = false;
      foreach ($results as $result) {
        if ((md5($login) == $result['login']) && (password_verify($pw, $result['password']) )) {
          // Found a match, store off our permissions
          SESSIONS['permissions'] = $result['permissions'];
          $loginFound = true;
          break;
        }
      }

      if (!loginFound) {
        // return to login with fail message
      }
      else {
        // Go to appropriate page based on permissions
      }

*/

?>