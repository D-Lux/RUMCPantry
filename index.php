<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = -1;
  include 'php/checkLogin.php';
  include 'php/header.php';
?>
	<div class="body-content">

  <div class="text-center">
    <h1 class="text-center">Welcome</h1>
    <h2 class="text-center">Roselle United Methodist Community Food Pantry Portal</h2>
    <a class="button" href="<?=$basePath?>mainpage.php">Sign-In Page</a>
	<br> <br>
	<a class="button" href="<?=$basePath?>gala2020.php">Gala Tickets & Info</a>
	<br> <br>
	<a class="button" href="https://roselle-umc-community-food-pantry.square.site/">Donate</a>
	
  </div>

  <br> <br> <br> 
 
  <style>
ul.a {
	aliign
    list-style-type: none;
    margin: 0;
    padding: 0;
}

li.a {
    display: inline;
	padding: 20px;
}

div.a {
	text-align: center;
}
</style>
	
  <div class="a">
	<ul class="a">
		<li class="a"><a href="https://www.facebook.com/Roselle-UMC-Community-Food-Pantry-292464297812413/"><span class="fab fa-facebook-square" style="font-size:48px"></span></a></li>
		<li class="a"><a href="http://www.roselleumcpantry.org:2096"><span class="fa fa-envelope" style="font-size:48px;color:red"></span></a></li>
	</ul>
    
  </div>
    
<?php include 'php/footer.php'; ?>