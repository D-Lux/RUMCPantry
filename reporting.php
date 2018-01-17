<?php 
include 'php/header.php';
include 'php/reportingOps.php';
include 'php/backButton.php';
?>

<h3>Reporting</h3>

<?php
  echo "<div class='body_content'>";
  
  $startDate = date('Y-m-d', strtotime('first day of last month'));
  $endDate = date('Y-m-d', strtotime('last day of last month'));
  
  // Create the input boxes for the start and end dates
  echo "<div class='inputDiv'>";
    echo "<label for='startDate'>Start Date:</label>
        <input id='startDate' type='Date' value='" . $startDate . "' onchange='AJAX_UpdateReport()'><br>";
    echo "<label for='endDate'>End Date:</label>
        <input id='endDate' type='date' value='" . $endDate . "' onchange='AJAX_UpdateReport()'><br>";
  echo "<br><hr><br></div>"; // </inputDiv>
  
  // Start the report div (updates through AJAX calls)
  echo "<div id='reportData'>";
  
  // Run the report queries using the default dates
  runReportQueries($startDate, $endDate);
  
  echo "</div>"; // </reportData>
  
  include 'php/footer.php'; 
?>

<script src="js/reportingOps.js"></script>