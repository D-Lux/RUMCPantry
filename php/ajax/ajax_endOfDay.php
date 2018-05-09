<?php
  session_start();
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
	include '../utilities.php';

	$conn = connectDB();

  $date = date("Y-m-d");

  // Build a table of people who did not complete the day properly
  $sql = "SELECT fm.firstName, fm.lastName, i.visitTime, i.invoiceID
        FROM invoice i
        JOIN client c
          ON i.clientID=c.clientID
        JOIN familymember fm
          On c.clientID=fm.clientID
        WHERE i.visitDate = '$date'
        AND fm.isHeadOfHousehold = true
        AND i.status != " . GetCompletedStatus() . "
        AND i.status < " . GetCompletedStatus() . "
        ORDER BY  i.visitTime ASC, fm.LastName ASC";

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