<?php
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
include '../utilities.php';

// passwords are generated using the password_hash function as follows
// password_hash("Test", PASSWORD_BCRYPT, ['cost' => 8]);
// password_verify("Test", "\$2y\$08\$aNuRvtdzYuk1nPJ1WAczWutZvVoCkA6XNslhVzebJB0X31PxH5SH6");


$name = $_POST['field1'];
$pw   = $_POST['field2'];

$data = [];
$data['msg'] = "";
$data['perm'] = "";

// If no password and email, grant permissions 1
if (empty($name)) {
  $data['msg'] = "<p>Please enter a login name</p>";
  $data['err']=1;
  die(json_encode($data));
}
elseif (empty($pw)) {
  $data['msg'] = "<p>Please enter a password</p>";
  $data['err']=1;
  die(json_encode($data));
}
else {
  $conn = null;
  if (strpos($name, "test")) {
    if (strpos($name, "home") !== false) {
      $conn = connectHomeDB();
    }
    else {
      $conn = connectTestDB();
    }
  }
  else {
    $conn = connectDB();
  }
      
  if ($conn -> connect_errno ) {
    $data['msg'] = "<p>Database Error: " . $conn->connect_errno . "</p>";
    $data['err']= 1;
    die(json_encode($data));
  }
  
  $sql = "SELECT login, pw, permission_level
          FROM permissions";
  $results = queryDB($conn, $sql);
  if ($results === false) {
    $data['msg'] = "<p>Error connecting to database</p>";
    $data['err']= 1;
    die(json_encode($data));
  }
  closeDB($conn);
  
  while($result = sqlFetch($results)) {
    if (strtoupper($name) == strtoupper($result['login'])) {
      if (password_verify($pw, $result['pw'])) {
        // We found a match, apply my permissions and break out
        $_SESSION['perms']= $result['permission_level'];
        $data['perm']     = $result['permission_level'];
        die(json_encode($data));
      }
      else {
        $data['msg'] = "<p>Incorrect password</p>";
        $data['err'] = 2;
        die(json_encode($data));
      }
    }
  }
  // Login not found
  $data['msg'] = "<p>This is not a valid login</p>";
  $data['err']  = 1;
  die(json_encode($data));
}

?>