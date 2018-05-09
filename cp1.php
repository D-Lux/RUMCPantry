<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 1;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';
?>

<style>
td, th {
  padding: 20px;
}
</style>
		<h3>Order Selection</h3>
		<div class='body-content'>
			<!-- Refresh button -->
			<button id='btn-back' onclick="location.reload()">Refresh</button>
			<?php
				$conn = connectDB();
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// *************************************************
				// ** Create a list of all active appointments today

				$sql = "SELECT clientInfo.fName as fName, clientInfo.lName as lName, invoice.clientID as clientID,
								status, visitDate, visitTime, invoiceID
              FROM invoice
              JOIN (SELECT firstName AS fName, lastName AS lName, familymember.clientID as clientID
                    FROM familymember
                  JOIN client
                  ON familymember.clientID = client.clientID
                  WHERE isHeadOfHousehold=1
                  AND client.isDeleted=0
                  AND client.redistribution=0
                  AND firstName <> 'Available'
                  AND lastName <> 'Available') as clientInfo
              ON clientInfo.clientID = invoice.clientID";
				$clientInfo = queryDB($conn, $sql);

				// Close the connection
				closeDB($conn);

				if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
					echo "No clients in the database.";
				}
				else {
					$noOrdersActive = true;

					while ($client = sqlFetch($clientInfo)) {
						if (IsReadyToCreateOrder($client['status'])) {
							if ($noOrdersActive) {
								echo "<table class='display'><tr><th>Client</th><th>Date</th><th>Time</th><th></th></tr>";
								$noOrdersActive = false;
							}

							echo "<tr><td>" . $client['lName'] . ", " . $client['fName'] . "</td>";
							echo "<td>" . $client['visitDate'] . "</td><td>" . DATE("h:i", strtotime($client['visitTime'])) . "</td>";

							echo "<form action='cof.php' method='post' >";
							echo "<input type='hidden' name='clientID' value=" . $client['clientID'] .">";
							echo "<input type='hidden' name='invoiceID' value=" . $client['invoiceID'] .">";
							echo "<input type='hidden' name='clientFirstName' value=" .  $client['fName'] .">";
							echo "<input type='hidden' name='clientLastName' value=" .  $client['lName'] .">";
							echo "<td><input type='submit' class='btn-table btn-edit' name='createOrder' value='Begin Order'>";
							echo "</td></form></tr>";
						}
						// Debug
						//else {
						//	echo "Order found with status: " . $client['status'] . "<br>";
						//}
					}
					if ($noOrdersActive) {
						echo "No orders are active at this time";
					}
					echo "</table>";
				}

			?>
<?php include 'php/footer.php'; ?>