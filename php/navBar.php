<div id="pantryNavBar">
  <ul>
    <li><a href="ap1.php">Main<i class="fa fa-caret"></i></a></li>
    <li>
      <a href="#">Registration<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="checkIn.php">Check In Page</a></li>
          <li><a href="ap_oo3.php">View Active Order Forms</a></li>
      </ul>
    </li>
    <li>
      <a href="#">Items<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="ap_io2.php">New Item</a></li>
          <li><a href="ap_io7.php">View Items</a></li>
          <li><a href="ap_oo2.php">Edit Order Forms</a></li>
          <li><a href="ap_io4.php">New Category</a></li>
          <li><a href="ap_io8.php">View Categories</a></li>
          <li><a href="ap_oo6.php">Adjust Category Ordering</a></li>
      </ul>
    </li>
    <li>
      <a href="#">Appointments<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="ap_ao1.php">View Appointments</a></li>
          <li><a href="ap_ao2.php">New Date</a></li>
      </ul>
    </li>
    <li>
      <a href="#">Clients<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="ap_co2.php">New Client</a></li>
          <li><a href="ap_co1.php">View Clients</a></li>
      </ul>
    </li>
    <li>
      <a href="#">Donations<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="ap_do1.php">View Donation Partners</a></li>
          <li><a href="ap_do2.php">New Donation</a></li>
          <li><a href="ap_do3.php">New Donaiton Partner</a></li>
      </ul>
    </li>
    <li>
      <a href="#">Admin<i class="fa fa-caret"></i></a>
      <ul>
          <li><a href="reporting.php">Reporting</a></li>
          <li><a href="adjustLogins.php">Adjust Logins</a></li>
      </ul>
    </li>
  </ul>
</div>



<style>
#pantryNavBar {
  background-color: #87CDFF;
  position: absolute;
  z-index: 9;
  text-align: center;
  width: 80%;
  height: 40px;
  margin: auto;
  margin-top: 5px;
  margin-left: -20px;
}
#pantryNavBar ul {
  padding: 0;
  margin: 0;
}
#pantryNavBar ul li {
  border-radius: 0 0 10px 10px;
  display: inline-block;
  background-color: #57B9FF;
  border: 1px #499BD6 solid;
  min-width: 100px;
}
#pantryNavBar ul li a {
  display: block;
  padding: 6px;
  text-decoration: none;
  color: black;
}
#pantryNavBar ul li:after {
  content: "";
  display: block;
  border-radius: 0 0 10px 10px;
  height: 5px;
}
#pantryNavBar ul ul {
  display: none;
  position: absolute;
  margin-top: 5px;
  border-radius: 10px;
}
#pantryNavBar ul ul li {
  display: block;
  min-width: 200px;
  border-radius: 10px;
}

/* hover */
#pantryNavBar ul li:hover {
  background-color: #93C5FF;
}
#pantryNavBar ul li:hover:after {
  background: #499BD6;
}
#pantryNavBar ul li:hover ul {
  display: block;
}
#pantryNavBar ul ul li:after {
  height: 0;
}
</style>