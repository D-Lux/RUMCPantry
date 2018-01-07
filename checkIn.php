<?php 
	include 'php/header.php';
	include 'php/backButton.php';
	
	$date = date("M j, Y");
	$dbDate = date("Y-m-d", strtotime($date));
	
	$conn = createPantryDatabaseConnection();
	if ($conn->connect_error) {
		echo "Connection failed: " . $conn->connect_error;
		die();
	}
	$sql = "UPDATE Invoice 
			SET status = " . GetActiveStatus() . " 
			WHERE visitDate = '" . $dbDate . "'
			AND status = " . GetAssignedStatus() . "";
	if ($conn->query($sql) === FALSE) {
		die("There was an error updating initial statuses");
	}
?>
<script type="text/javascript" charset="utf8" src="includes/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/checkInStyle.css">
<h3>Registration Room: <?= $date ?> </h3>

<div class="clearfix"></div>
<div id="msgField"></div>

<div class="tab">
  <button class="tablinks" id="Arrive">Due In <p id="arriveCount"></p><br><i class="fa fa-sign-in fa-3x"></i></button>
  <button class="tablinks" id="Order">To Order <p id="orderCount"></p><br><i class="fa fa-wpforms fa-3x"></i></button>
  <button class="tablinks" id="Review">To Review <p id="reviewCount"></p><br><i class="fa fa-edit fa-3x"></i></button>
  <button class="tablinks" id="Print">To Print <p id="printCount"></p><br><i class="fa fa-print fa-3x"></i></button>
  <button class="tablinks" id="Wait">Waiting <p id="waitCount"></p><br><i class="fa fa-coffee fa-3x"></i></button>
  <button class="tablinks" id="Complete">Completed <p id="completeCount"></p><br><i class="fa fa-check fa-3x"></i></button>
</div>

  <div id="ArriveContent" class="tabcontent defaultTab">
    <h3>Due In</h3>
  </div>

  <div id="OrderContent" class="tabcontent">
    <h3>Ordering</h3>
  </div>

  <div id="ReviewContent" class="tabcontent">
    <h3>Review</h3>
  </div>

  <div id="PrintContent" class="tabcontent">
    <h3>Print</h3>
  </div>
  <div id="WaitContent" class="tabcontent">
    <h3>Waiting</h3>
  </div>
  <div id="CompleteContent" class="tabcontent">
    <h3>Completed</h3>
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
    
    $("#Arrive").click();
	
    function ajax_PerformAction(obj) {
      $.ajax({
        url     : 'php/ajax/checkIn_Advance.php',
        dataType: "json",
        type    : 'POST',
        data    : { field1: '<?= $dbDate ?>', field2: obj.id, },
        success : function(data) {
          ("#msgField").html(data);
          // Action was successful, pop an extra update immediately
          $.ajax({
            url     : 'php/ajax/ajax_checkIn.php',
            dataType: "json",
            type    : 'POST',
            data    : { field1: "<?= $dbDate ?>", },
            success : function(data) {
              $('#ArriveContent').html(data.due);
              $('#OrderContent').html(data.order);
              $('#ReviewContent').html(data.review);
              $('#PrintContent').html(data.print);
              $('#WaitContent').html(data.wait);
              $('#CompleteContent').html(data.complete);

              $('#arriveCount').html(data.dueCount);
              $('#orderCount').html(data.orderCount);
              $('#reviewCount').html(data.reviewCount);
              $('#printCount').html(data.printCount);
              $('#waitCount').html(data.waitCount);
              $('#completeCount').html(data.completeCount);
              
              $('.btn_Action').off('click').on('click', function() {
                ajax_PerformAction(this);
              });
          
              $('#msgField').html(data.error);
            },
          });
        }
      });
    }
  
    (function tabRefresher() {
      $.ajax({
        url     : 'php/ajax/ajax_checkIn.php',
        dataType: "json",
        type    : 'POST',
        data    : { field1: "<?= $dbDate ?>", },
        success : function(data) {
          $('#ArriveContent').html(data.due);
          $('#OrderContent').html(data.order);
          $('#ReviewContent').html(data.review);
          $('#PrintContent').html(data.print);
          $('#WaitContent').html(data.wait);
          $('#CompleteContent').html(data.complete);

          $('#arriveCount').html(data.dueCount);
          $('#orderCount').html(data.orderCount);
          $('#reviewCount').html(data.reviewCount);
          $('#printCount').html(data.printCount);
          $('#waitCount').html(data.waitCount);
          $('#completeCount').html(data.completeCount);
          
          $('.btn_Action').off('click').on('click', function() {
            ajax_PerformAction(this);
          });
		  
          $('#msgField').html(data.error);
        },
        complete  : function() {
          // Schedule the next request when the current one's complete
          setTimeout(tabRefresher, 5000);
        }
      });
    })();

    $(document).ready(function() {
      // run the first time; all subsequent calls will take care of themselves
      setTimeout(tabRefresher, 5000);
    });
  </script>
  <div class="clearfix"></div>
	<button class='btn_walkIn' onclick="location.href = 'endOfDay.php';">End of day</button>
	</div><!-- /body_content -->
	</div><!-- /content -->

</body>
</html>