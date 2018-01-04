<?php 
	include 'php/header.php';
	include 'php/backButton.php';
?>
<script type="text/javascript" charset="utf8" src="includes/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">
<h3>Registration Room: 
<?php
    $date = date("M j, Y");
    echo "$date </h3>";

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

?>
<div class="clearfix"></div>
<style>

/* Style the tab pane */
.tab {
  float: left;
  border-style: solid none solid solid;
  border-width: 1px;
  border-color: #ccc;
  /*background-color: #f1f1f1;*/
  width: 20%;
  height: 500px;
}

/* Style the buttons inside the tab */
.tab button {
  display: block;
  background-color: #57B9FF;
  color: black;
  width: 100%;
  height: 16.667%;
  outline: none;
  text-align: center;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #93C5FF;
}

/* Create an active/current "tab button" class */
.tab button.activeButton {
  background-color: #E5E5E5;
  color: DodgerBlue;
  border: none;
}

/* Style the tab content */
.tabcontent {
  float: left;
  padding: 0px 12px;
  border: 1px solid #ccc;
  background-color: #E5E5E5;
  width: 80%;
  border-left: none;
  height: 500px;
  display: none;
}
.active {
  display: block;
}
</style>
<div class="tab">
  <button class="tablinks defaultTab" id="Arrive">Due In<br><i class="fa fa-sign-in fa-3x"></i></button>
  <button class="tablinks" id="Order">To Order<br><i class="fa fa-wpforms fa-3x"></i></button>
  <button class="tablinks" id="Review">To Review<br><i class="fa fa-edit fa-3x"></i></button>
  <button class="tablinks" id="Print">To Print<br><i class="fa fa-print fa-3x"></i></button>
  <button class="tablinks" id="Wait">Waiting<br><i class="fa fa-coffee fa-3x"></i></button>
  <button class="tablinks" id="Complete">Completed<br><i class="fa fa-check fa-3x"></i></button>
</div>

  <div id="ArriveContent" class="tabcontent">
    <h3>Due In</h3>
    <p>London is the capital city of England.</p>
  </div>

  <div id="OrderContent" class="tabcontent">
    <h3>Ordering</h3>
    <p>Paris is the capital of France.</p>
  </div>

  <div id="ReviewContent" class="tabcontent">
    <h3>Review</h3>
    <p>Tokyo is the capital of Japan.</p>
  </div>

  <div id="PrintContent" class="tabcontent">
    <h3>Print</h3>
    <p>Tokyo is the capital of Japan.</p>
  </div>
  <div id="WaitContent" class="tabcontent">
    <h3>Waiting</h3>
    <p>Tokyo is the capital of Japan.</p>
  </div>
  <div id="CompleteContent" class="tabcontent">
    <h3>Completed</h3>
    <p>Tokyo is the capital of Japan.</p>
  </div>
</body>
  <script>
    $(".tablinks").on("click", function() {
      $(".tabcontent").removeClass("active");
      $(".tablinks").removeClass("activeButton");
      var openTab = "#" + this.id + "Content";
      $(openTab).addClass("active");
      $(this).addClass("activeButton");
    });
    
    $(".defaultTab").click();
  </script>
	<button class='btn_walkIn' onclick="location.href = 'endOfDay.php';">End of day</button>
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>
</html>