<?php

	include '../utilities.php';

	$conn = connectDB();

  $date = date("Y-m-d");  

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

$results = runQuery($conn, $sql);
closeDB($conn);
$resultCount = is_array($results) ? count($results) : 0;
if ($resultCount > 0) {
  echo"<h3>Incomplete Clients</h3>";
  $rowTitle = null;
  foreach ($results as $result) {
    $invoiceID=$result["invoiceID"];
    if($rowTitle != $result["visitTime"]) {
      if ($rowTitle != null) {
        echo "</table>"; 
      }
      $rowTitle = $result["visitTime"];           
      echo "<b>" . returnTime($rowTitle). "</b>";
      echo "<table>";
      echo "<tr><th>First name</th><th>Last name</th><th>Actions</th></tr>";
     }
    ?>
    <tr>
      <td><?=$result['firstName']?></td><td><?=$result['lastName']?></td>
      <td>
        <button class="btn-edit btn-no-show"  value=<?=$invoiceID?>><i class='fa fa-eye-slash'></i> No Show</button>
        <button class="btn-edit btn-documentation" value=<?=$invoiceID?>><i class='fa fa-file'></i> Bad Documentation</button>
        <button class="btn-edit btn-cancelled" value=<?=$invoiceID?>><i class='fa fa-ban'></i> Cancelled </button>
      </td>
    </tr>   
    <?php               
  }
   echo "</table>";
}    
else {
  echo "End of Day Successful!";
}     

?>