<?php

if (($_COOKIE["loggedin"]) != 1) {
	header ("location: /RUMCPantry/login.php?err=2");
}

/*
echo "<br>";
      //$pw = password_hash("Test", PASSWORD_BCRYPT, ['cost' => 8]);
      $result = password_verify("Test", "\$2y\$08\$aNuRvtdzYuk1nPJ1WAczWutZvVoCkA6XNslhVzebJB0X31PxH5SH6");
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