<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';

	$date = date("M j, Y");
	$dbDate = date("Y-m-d", strtotime($date));

	$conn = connectDB();
	if ($conn->connect_error) {
		echo "Connection failed: " . $conn->connect_error;
		die();
	}
	$sql = "UPDATE invoice
			SET status = " . GetActiveStatus() . "
			WHERE visitDate = '" . $dbDate . "'
			AND status = " . GetAssignedStatus() . "";
	if ($conn->query($sql) === FALSE) {
		die("There was an error updating initial statuses");
	}
?>


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

  <div id="ArriveContent" class="tabcontent defaultTab"></div>

  <div id="OrderContent" class="tabcontent"></div>

  <div id="ReviewContent" class="tabcontent"></div>

  <div id="PrintContent" class="tabcontent"></div>
  <div id="WaitContent" class="tabcontent"></div>
  <div id="CompleteContent" class="tabcontent"></div>

  <div class="clearfix"></div>
  <div class="row">
    <div class="col-sm-4">
      <a href="/RUMCPantry/endOfDay.php" class="button">End of day</a>
    </div>
    <div class="col-sm-1">
    <div class="col-sm-4">
      <!-- <a href="/RUMCPantry/awc.php" class="button">Add Walk-In</a> -->
    </div>
  </div>


<?php include 'php/footer.php'; ?>

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
      url      : 'php/ajax/checkIn_Advance.php',
      dataType : 'json',
      type     : 'POST',
      data     : { field1: '<?=$dbDate?>', field2: obj.id, },
      success  : function(data) {
        // If we're getting redirected to a new page, go there
        if (typeof data.link !== 'undefined') {
          var pageToLoad = data.link.substring(10);
          window.location.assign(pageToLoad);
        }
        else {
          // $("#msgField").html(data.Message);
          // Action was successful, pop an extra update immediately
          $.ajax({
            url     : 'php/ajax/ajax_checkIn.php',
            dataType: "json",
            type    : 'POST',
            data    : { field1: '<?=$dbDate?>', },
            success : function(data) {
              $('#ArriveContent').html(data.due);
              $('#OrderContent').html(data.order);
              $('#ReviewContent').html(data.review);
              $('#PrintContent').html(data.print);
              $('#WaitContent').html(data.wait);
              $('#CompleteContent').html(data.completed);

              $('#arriveCount').html(data.dueCount);
              $('#orderCount').html(data.orderCount);
              $('#reviewCount').html(data.reviewCount);
              $('#printCount').html(data.printCount);
              $('#waitCount').html(data.waitCount);
              $('#completeCount').html(data.completedCount);

              $('.btn_Action').off('click').on('click', function() {
                ajax_PerformAction(this);
              });

              $('#msgField').html(data.error);
            }
          });
        }

      },
    });
  }

  (function tabRefresher() {
    $.ajax({
      url     : 'php/ajax/ajax_checkIn.php',
      dataType: 'json',
      type    : 'POST',
      data    : { field1: '<?=$dbDate?>', },
      success : function(data) {
        // TODO: compare with current HTML, if same, don't update
        $('#ArriveContent').html(data.due);
        $('#OrderContent').html(data.order);
        $('#ReviewContent').html(data.review);
        $('#PrintContent').html(data.print);
        $('#WaitContent').html(data.wait);
        $('#CompleteContent').html(data.completed);

        $('#arriveCount').html(data.dueCount);
        $('#orderCount').html(data.orderCount);
        $('#reviewCount').html(data.reviewCount);
        $('#printCount').html(data.printCount);
        $('#waitCount').html(data.waitCount);
        $('#completeCount').html(data.completedCount);

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