<?php
include('utilities.php');
include('checkLogin.php');
?>

<!DOCTYPE html>
  <html>
    <head>
      <title>Roselle UMC</title>

      <link href='includes/fontawesome/css/font-awesome.min.css' rel='stylesheet' type='text/css' >
      <link href='includes/bootstrap.min.css' rel='stylesheet' type='text/css' >
      <link href='includes/chosen/chosen.min.css' rel='stylesheet' type='text/css' >
      <link href='includes/jquery.dataTables.min.css' rel='stylesheet' type='text/css' >
      <link href='css/style.css' rel='stylesheet'>
    </head>
    <body>
      <header>
        <h1 class='rumc'>Roselle UMC</h1>
        <h2 class='rumc'>Community Food Pantry</h2>
        <?php if (isset($pageRestriction) && $pageRestriction > 0  && $_SESSION['perms'] >= 99) { include('navBar.php'); }?>
      </header>

      <div class='content'>


