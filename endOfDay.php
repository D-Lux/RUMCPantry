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
        ORDER BY  Invoice.visitTime ASC, FamilyMember.LastName ASC";

$result = returnAssocArray(queryDB($conn, $sql));
if (count($result) > 0) {
  echo"<h3>Incomplete Clients</h3>";
    // output data of each row
    $rowTitle = null;
    while($row = $result->fetch_assoc()) {
      if($rowTitle != $row["visitTime"]) {
        if ($rowTitle != null) {
          echo "</table>"; 
        }
          $rowTitle = $row["visitTime"];
          echo "<b>";            
          echo returnTime($rowTitle);
          echo "</b>";
      }


      echo "<table>";
      echo "<tr><th>First name</th><th>Last name</th><th>No show?</th><th>Incorrect documentation?</th><th>Cancelled?</th></tr>";
      echo "<tr>";
      //grab donation id
      echo "<form action='endOfDay.php' method='post'>";
      $invoiceID=$row["invoiceID"];
      echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
      echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td>";
      echo "<td><input type='submit' name='noShow' value='No show'></td>";
      echo "<td><input type='submit' name='documentation' value='Documentation'></td>";
      echo "<td><input type='submit' name='cancelled' value='Cancelled'></td>";
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
