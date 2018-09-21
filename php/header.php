<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<div style="float:left;z-index:200;position: absolute;" class="hide_for_print">
  <div id="google_translate_element"></div>
</div>

<!DOCTYPE html>
  <html>
    <head>
        <!--<link rel="icon" href="<?=$basePath?>images/Cannedfood.ico">-->
        <?php if (isset($newTitle)) { ?>
            <title><?=$newTitle?> - Roselle UMC</title>
        <?php } else { ?>
            <title>Roselle UMC</title>
        <?php } ?>
      

      <link href="<?=$basePath?>includes/fontawesome/css/fa-brands.min.css" rel="stylesheet" type="text/css" >
      <link href='<?=$basePath?>includes/fontawesome/css/font-awesome.min.css' rel='stylesheet' type='text/css' >
      <link href='<?=$basePath?>includes/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css' >
      <link href='<?=$basePath?>includes/chosen/chosen.min.css' rel='stylesheet' type='text/css' >
      <link href='<?=$basePath?>includes/jquery.dataTables.min.css' rel='stylesheet' type='text/css' >
      <link href='<?=$basePath?>css/style.css' rel='stylesheet'>
      <link rel="shortcut icon" type="image/x-icon" href="<?=$basePath?>/images/grocerybag.ico">
    </head>
    <body>
      <header>
        <h1 class='rumc'>Roselle UMC</h1>
        <h2 class='rumc'>Community Food Pantry</h2>
        <?php if (isset($pageRestriction) && $pageRestriction > 0  && $_SESSION['perms'] >= 99) { include('navBar.php'); }?>
      </header>

      <div class='content'>


