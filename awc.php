<?php 
include 'php/header.php';
include 'php/backButton.php'; 
?>

<link rel="stylesheet" type="text/css" href="includes/bootstrap/css/bootstrap.min.css">
	<style>
		.awc_hidden {
			display: none;
			padding-top: 10px;
		}
		input {
			margin: 0px !important;
		}
		.row {
			margin: 0px 0px 10px 0px;
		}
	</style>
	
    
	<h3>Add Walk-In</h3>
	
	<div class="body_content">
	
	<?php
	
		// *******************************************
		// ** Generate the datalist for client drop down
		
		$conn = connectDB();
		$sql = "SELECT firstName AS fName, lastName AS lName, FamilyMember.clientID as clientID
				FROM FamilyMember
				JOIN Client
				ON Client.clientID=FamilyMember.clientID
				WHERE isHeadOfHousehold=1
				AND Client.redistribution=0
				AND Client.isDeleted=0
				AND (firstName <> 'Available'
				AND lastName <> 'Available')";
		$clientInfo = queryDB($conn, $sql);

		if (($clientInfo == NULL) || ($clientInfo->num_rows <= 0)) {
			//echo "No clients in the database.";
      // TODO: Add some sort of warning instead
			//echoDivWithColor("Error description: " . mysqli_error($conn), "red");
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
		
		echo "<div id='awc_options'>";
			echo "<input type='button' class='btn-nav' onclick='toggleOption(this)' name='existingOption' value='Existing Client'>";
			echo "<input type='button' class='btn-nav' onclick='toggleOption(this)' name='newOption' value='New Client'>";
		echo "</div>";
	
	
		echo "<div id='existingOption' class='awc_hidden' >";
			echo "<form id='existingForm' action='php/apptOps.php' method='post' >";
			echo "Name: ";
			echo $clientDataList . "<br><br>";
      echo "<input type='hidden' name='existingWalkIn' value=1>";
			echo "<input type='hidden' ID='clientID-hidden' name='clientID' value=-1>";
			echo "<input type='submit' name='existingWalkIn' value='Add Walk-In'>";
			echo "</form>";
		echo "</div>";
		
		echo "<div id='newOption' class='awc_hidden' >";

		?>
		<form name="addClient" onSubmit="return validateNewWalkIn()" action="php/clientOps.php" method="post">
			<!-- Required fields -->
			<div class="row">
				<div class="col-sm-4"><label class="required">First Name: </label></div>
				<div class="col-sm-8"><input type="text" name="clientFirstName" maxlength="45"></div>
			</div>
			<div class="row">
				<div class="col-sm-4"><label class="required">Last Name: </label></div>
				<div class="col-sm-8"><input type="text" name="clientLastName" maxlength="45"></div>
			</div>
			<div class="row">
				<div class="col-sm-4"><label class="required">Date of Birth: </label></div>
				<div class="col-sm-8"><input type="date" name="birthDate" min="1900-01-01"></div>
			</div>
			<div class="row">
				<div class="col-sm-4"><label class="required">Number of Adults: </label></div>
				<div class="col-sm-8"><input type="number" name="numAdults" id="numAdultsField" min=1 value=1></div>
			</div>
			
			<!-- number of kids -->
			<div class="row">
				<div class="col-sm-4">Number of Children:</div>
				<div class="col-sm-8"><input type="number" name="numKids" min=0 value=0></div>
			</div>
	
			<!-- Gender Selection -->
			<div class="row">
				<div class="col-sm-4">Gender: </div>
				<div class="col-sm-8">
					<select name="gender">
						<option value=0>-</option>
						<option value=-1>Male</option>
						<option value=1>Female</option> 
					</select>
				</div>
			</div>
			
			<!-- Dropdown for client type -->
			<div class="row">
				<div class="col-sm-4">Client Type:</div>
				<div class="col-sm-8">
					<select name="clientType"> 
						<option value=0>Unknown</option>
						<option value=1>Constituent</option>
						<option value=2>Member</option>
						<option value=3>Resident</option>
					</select>
				</div>
			</div>
			
			<!-- Dropdown for food stamps -->
			<div class="row">
				<div class="col-sm-4">Food Stamp Status:</div>
				<div class="col-sm-8">
					<select name="foodStamps">
						<option value=-1>Unknown</option>
						<option value=1>Yes</option>
						<option value=0>No</option> 
					</select>
				</div>
			</div>
			
			<?php
				function makeArraySet($header, $inputType, $fieldName) {
					return array("Header" => $header, "inputType" => $inputType, "fieldName" => $fieldName);
				}
				$simpleRows = array(
									makeArraySet("Email"		      , "email"	, "email"		      ),
									makeArraySet("Phone Number"	  , "tel"	  , "phoneNo"		    ), 
									makeArraySet("Street Address" , "text"	, "addressStreet" ),
									makeArraySet("City"			      , "text"	, "addressCity"   ),
									makeArraySet("Zip Code" 	    , "text"	, "addressZip"	 ));
				
				foreach ($simpleRows as $row) {
					echo 	"<div class='row'>
								<div class='col-sm-4'>" . $row['Header'] . ":</div>
								<div class='col-sm-8'>
									<input type='" . $row['inputType'] . "' name='" . $row['fieldName'] . "'>
								</div>
							</div>";
				}
			?>
			
			<!-- dropdown for state -->
			<div class="row">
				<div class="col-sm-4">State:</div>
				<div class="col-sm-8">
					<select id="addressStateInput" name="addressState">
						<?php
							getStateOptions("IL");
						?>
					</select>
				</div>
			</div>
			
			<br>
			<input type="hidden" name="newWalkIn" value=1>
			<input type="submit" name="submitClient" value="Add Walk-In" >

		</form>
		</div>	<!-- End of hidden add-walk-in -->
		
<?php include 'php/footer.php'; ?>
<script src="js/walkInOps.js"></script>