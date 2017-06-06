<?php 

	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "foodpantry";
	// previous lines set up the strings for connextion

	mysql_connect($servername, $username, $password);
	mysql_select_db($dbname);
	
	// get the last name of all active clients that are heads of household
	$sql = "SELECT lastName FROM FamilyMember 
			JOIN Client ON FamilyMember.familyMemberID=Client.clientID
			WHERE Client.isDeleted=0 AND
			FamilyMember.isHeadOfHousehold=1";
	$result = mysql_query($sql);
	
	echo "<div id='clientNames'>";
	
	echo "<select name='clientName'><option value=''></option>";
	while ($row = mysql_fetch_array($result)) {
		echo "<option value='" . $row['lastName'] . "'>" . $row['lastName'] . "</option>";
	}
	echo "</div>";

?>