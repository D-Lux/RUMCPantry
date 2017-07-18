<!DOCTYPE html>

<html>
	<head>
		<title>Roselle United Methodist Church Food Pantry</title>
		<script src="js/utilities.js"></script>
		<link href='style.css' rel='stylesheet'>
		<?php include 'php/utilities.php'; ?>

	</head>
	<body>
		<script>
		// This function updates a hidden input field with the appropriate clientID
		function updateHiddenClientID() {
			var input = document.getElementById('clientID');
			list = input.getAttribute('list');
			options = document.querySelectorAll('#' + list + ' option');
			hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');
			label = input.value;

			hiddenInput.value = label;

			for(var i = 0; i < options.length; i++) {
				var option = options[i];
				if(option.innerText === label) {
					hiddenInput.value = option.getAttribute('data-value');
					break;
				}
			}
		}
		 
		// Client made their appointment and was sent back to this screen
		if (getCookie("clientApptSet") != "") {
			window.alert("Appointment set! Thank you, see you next time!");
			removeCookie("clientApptSet");
		}
		</script>
		<button onclick="goBack()">Back</button>
		
		<h1>Roselle United Methodist Church</h1>
		<h2>Food Pantry</h2>
		<h3>Client Selection</h3>
	
		<?php
			$conn = createPantryDatabaseConnection();
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			// *******************************************
			// ** Generate the datalist for client drop down
			// ** Restrict dropdown to people with appointments today
			
			$sql = "SELECT firstName AS fName, lastName AS lName, FamilyMember.clientID as clientID
					FROM FamilyMember
					JOIN Client
					ON FamilyMember.clientID = Client.clientID
					WHERE isHeadOfHousehold=1
					AND Client.isDeleted=0
					AND Client.redistribution=0
					AND (firstName <> 'Available'
					AND lastName <> 'Available')";
			$clientInfo = queryDB($conn, $sql);

			if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
				echo "No clients in the database.";
			}
			
			// Generate the string we'll need to display the client datalist
			$clientDataList = "<input type='text' name='clientName' list='Clients' autocomplete='off' id='clientID'";
			$clientDataList .= " onchange='updateHiddenClientID()'><datalist id='Clients' >";
			while($client = sqlFetch($clientInfo) ) {
				$clientDataList .= "<option data-value=" . $client['clientID'] . ">";
				$clientDataList .= $client['lName'] . ", " . $client['fName'] . "</option>";

			}
			$clientDataList .= "</datalist>";
			
			// Close the connection
			closeDB($conn);
		

			echo "<form action='cp2.php' method='post' >";
			echo "Name: ";
			echo $clientDataList . "<br><br>";
			echo "<input type='hidden' ID='clientID-hidden' name='clientID' value=0>";
			echo "<input type='submit' value='Continue'>";
			echo "</form>";
			

		?>		

	</body>
</html>