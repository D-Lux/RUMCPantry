<?php 
include 'php/header.php';
include 'php/backButton.php';
?>
<h3>End of day</h3>

<div class="body_content">
<?php

// Future: May allow them to select the day of check in page in the future
$date = date("Y-m-d");  

// Create and test connection
$conn = createPantryDatabaseConnection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Build a table of people who did not complete the day properly
$sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID
        FROM Invoice 
        JOIN Client 
          ON Invoice.clientID=Client.clientID 
        JOIN FamilyMember 
          On Client.clientID=FamilyMember.clientID 
        WHERE Invoice.visitDate = '$date'
        AND FamilyMember.isHeadOfHousehold = true 
        AND Invoice.status != " . GetCompletedStatus() . "
        AND Invoice.status < " . GetCompletedStatus() . " 
        ORDER BY  Invoice.visitTime ASC, FamilyMember.LastName ASC";

$results = returnAssocArray(queryDB($conn, $sql));
if (count($results) > 0) {
  echo"<h3>Incomplete Clients</h3>";
  $rowTitle = null;
  foreach ($results as $result) {
    if($rowTitle != $result["visitTime"]) {
      if ($rowTitle != null) {
        echo "</table>"; 
      }
      $rowTitle = $result["visitTime"];           
      echo "<b>" . returnTime($rowTitle). "</b>";
      echo "<table>";
      echo "<tr><th>First name</th><th>Last name</th><th>Actions</th></tr>";
     }
    
    echo "<tr>";
    //grab donation id
    echo "<form action='endOfDay.php' method='post'>";
    $invoiceID=$result["invoiceID"];
    echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
    echo "<td>". $result["firstName"]. "</td><td>". $result["lastName"]. "</td>";
    echo "<td>";
      echo "<button type='submit' class='btn_edit' name='noShow'><i class='fa fa-eye-slash'></i> No Show</button>";
      echo "<button type='submit' class='btn_edit' name='documentation'><i class='fa fa-file'></i> Bad Documentation</button>";
      echo "<button type='submit' class='btn_edit' name='cancelled'><i class='fa fa-ban'></i> Cancelled </button>";
    echo "</td>";
    echo "</form>";
    echo "</tr>";    
                   
  }
   echo "</table>";
}    
else {
  echo "End of Day Successful!";
}     


  

 /*when the button is pressed on post request*/
if(isset($_POST['noShow'])) {    
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetNoShowStatus() . " WHERE invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
}

/*when the button is pressed on post request*/
elseif(isset($_POST['documentation'])) {
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetBadDocumentationStatus() . " WHERE invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
}
/*when the button is pressed on post request*/
elseif(isset($_POST['cancelled'])) {
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetCanceledStatus() . " WHERE invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
}

include 'php/footer.php'; 
?>
