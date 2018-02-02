<?php 
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
if ($name == null || $name == "" ) {
  if ($pw == null || $pw == "") {
    $_SESSION['perms'] = 1;
    $data['perm'] = 1;
    die(json_encode($data));
  }
  // Else request a login (login was blank, password was filled out)
  else {
    $data['msg'] = "<p>Please enter a login name</p>";
    $data['err']=1;
    die(json_encode($data));
  }
}
else {
  $conn = connectDB();
  $sql = "SELECT login, pw, permission_level
          FROM permissions";
  $results = queryDB($conn, $sql);
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
  $data['msg'] .= "<p>This is not a valid login</p>";
  $data['err']  = 1;
  die(json_encode($data));
}

?>