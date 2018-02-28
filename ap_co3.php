<?php
  $pageRestriction = 10;
	include 'php/header.php';
	include 'php/backButton.php';
?>
	
	<div class="body-content">
	
	<?php
		// Set up server connection
		$conn = connectDB();
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// *************************************************
		// Query the database
		// It'll be easy to just grab the queries here for all the data we will display layer
		
		// Grab client information
		$sql = "SELECT numOfAdults, numOfKids, email, phoneNumber, address, city, state, zip, foodStamps, notes, clientType
				FROM Client
				WHERE clientID=" . $_GET['id'];
		$clientInfo = queryDB($conn, $sql);
		$familySize = 1;
		
		// Grab family member information
		$sql = "SELECT firstName, lastName, isHeadOfHousehold, notes, birthDate, gender, FamilyMemberID
				FROM FamilyMember
				WHERE clientID=" . $_GET['id'] . "
				AND isDeleted<>1";
		$familyInfo = queryDB($conn, $sql);
				
		// Grab the client family name
		$sql = "SELECT lastName 
				FROM FamilyMember
				JOIN Client
				ON FamilyMember.ClientID = Client.ClientID
				WHERE isHeadOfHousehold = TRUE
				AND FamilyMember.ClientID = " . $_GET['id'];
		$clientNameInfo = sqlFetch(queryDB($conn, $sql));
		$clientName = $clientNameInfo['lastName'];
		
		// Close the connection as we've gotten all the information we should need
		// Using function in utilities.php to close the database
		closeDB($conn);
	
		// ***********************************************************************
		// DISPLAY CLIENT INFORMATION
		// Fills in all fields with values from the database
		// "Update" button at end will take all values and update the client entry with those values
		
		if (($clientInfo->num_rows > 0) AND ($familyInfo->num_rows > 0)) {
			$clientRow 		 = sqlFetch($clientInfo);
			$familySize 	 = $clientRow['numOfKids'] + $clientRow['numOfAdults'];
			$foodStampStatus = $clientRow['foodStamps'];
			$clientType 	 = $clientRow['clientType'];
			?>
			
			<h3>Client: <?= $clientName ?> </h3><br>
			
			<form name='updateClient' action='php/clientOps.php' method='post'>
			<input type='hidden' name='id' value='<?= $_GET['id'] ?>'>
			<!-- Number of Adults -->
			<div class="row">
				<div class="col-sm-4"><label class="required">Number of Adults:</label></div>
				<div class="col-sm-8">
					<input id='numAdultsInput' type='number' min=1 name='numAdults' value=<?= $clientRow['numOfAdults'] ?> >
				</div>
			</div>
			<!-- Number of Children -->
			<div class="row">
				<div class="col-sm-4">Number of Children:</div>
				<div class="col-sm-8">
					<input id='numKidsInput' type='number' name='numKids' value=<?= $clientRow['numOfKids'] ?> >
				</div>
			</div>
			<!-- Phone Number -->
			<div class="row">
				<div class="col-sm-4">Phone Number:</div>
				<div class="col-sm-8">
					<input type='tel' name='phoneNo' value='<?= displayPhoneNo($clientRow['phoneNumber']) ?>' >
				</div>
			</div>
			<!-- Email -->
			<div class="row">
				<div class="col-sm-4">Email:</div>
				<div class="col-sm-8">
					<input id='emailInput' type='email' name='email' value='<?= $clientRow['email'] ?>'>
				</div>
			</div>
			<!-- ------- Address ---- -->
			<div class="row">
				<div class="col-sm-2"><strong>Address</strong></div>
			</div>
			<div style="border: 2px solid #499BD6; padding:5px;margin-top:3px;">
				<!-- street Address -->
				<div class="row">
					<div class="col-sm-4">Street Address:</div>
					<div class="col-sm-8">
						<input id='addressStreetInput' type='text' name='addressStreet' value='<?= $clientRow['address'] ?>' >
					</div>
				</div>
				<!-- city -->
				<div class="row">
					<div class="col-sm-4">City:</div>
					<div class="col-sm-8">
						<input id='addressCityInput' type='text' name='addressCity' value='<?= $clientRow['city'] ?>' >
					</div>
				</div>
				<!-- dropdown for state -->
				<div class="row">
					<div class="col-sm-4">State:</div>
					<div class="col-sm-8">
						<select id="addressStateInput" id="addressState" name="addressState">
							<?php
								getStateOptions($clientRow['state']);
							?>
						</select>
					</div>
				</div>
				<!-- zipcode -->
				<div class="row">
					<div class="col-sm-4">Zip Code</div>
					<div class="col-sm-8">
						<input type='number' id='addressZipField' name='addressZip' value=<?= $clientRow['zip'] ?> >
					</div>
				</div>
			</div>
			<br>
			<!-- Foodstamp Status -->
			<div class="row">
				<div class="col-sm-3">Foodstamp Status:</div>
				<div class="col-sm-3">
					<select id='foodStampsInput' name='foodStamps'>
						<?php 
							echo "<option value=-1 " . ($foodStampStatus == -1 ? "selected" : "") . ">Unknown</option>";
							echo "<option value=1 "  . ($foodStampStatus == 1  ? "selected" : "") . ">Yes</option>";
							echo "<option value=0 "  . ($foodStampStatus == 0  ? "selected" : "") . ">No</option>";
						?>
					</select>
				</div>
			<!-- Client Type -->
				<div class="col-sm-3">Client Type:</div>
				<div class="col-sm-3">
					<select id='clientTypeInput' name='clientType'> 
						<?php echo "
							<option value=0 " . ($clientType == 0 ? "selected" : "") . ">Unknown</option>
							<option value=1 " . ($clientType == 1 ? "selected" : "") . ">Constituent</option>
							<option value=2 " . ($clientType == 2 ? "selected" : "") . ">Member</option>
							<option value=3 " . ($clientType == 3 ? "selected" : "") . ">Resident</option>
							</select>";
						?>
					</select>
				</div>
			</div>
			<br>
			<!-- Notes -->
			<div class="row">
				<div class="col-sm-4">Notes:</div>
				<div class="col-sm-8">
					<textarea id='notesInput' class='notes' type='text' name='notes'><?= $clientRow['notes'] ?></textarea>
				</div>
			</div>

			
			<input class="btn-nav" type="submit" name="UpdateClient" value="Save">
			</form>
		
			
			
			<?php
			
			
			
			// ***********************************************************************
			// DISPLAY FAMILY MEMBERS
			
			echo "<br><br><br><h3>Family Members</h3>";
			
			// Pulls all members of the family
			// firstName | lastName | isHeadOfHousehold | birthdate | notes | Delete
			// Create the family table and add in the headers
			echo "<table> <tr> <th></th>";
			echo "<th>First Name</th><th>Last Name</th>";
			echo "<th>Birth Date</th><th>Gender</th>";
			echo "<th>Head of Household</th>";//<th>Notes</th>";
			
			$showDeleteColumn = ($familyInfo->num_rows > 1);
			if ($showDeleteColumn) {
				echo "<th></th>";
			}
			echo "</tr>";
			
			// Loop through our data and spit it out
			while( $row = sqlFetch($familyInfo) ) {
				// Start the form for this row (so buttons act correctly based on member)
				echo "<form action='php/clientOps.php' >";
				
				// List the member ID so we can do updates and inactivations
				echo "<input type='hidden' name='memberID' value=" . $row['FamilyMemberID'] . ">";
				
				// Pass along the client ID in case we fudge with the head of household later
				echo "<input type='hidden' name='clientID' value='" . $_GET['id'] . "'>";
				
				// Start Table row
				echo "<tr>";

					// Update button
        echo "<td><button type='submit' class='btn-table btn-edit' 
					     name='GoUpdateMember' value='View'><i class='fa fa-eye'> View</i></button></td>";
				
				// Various basic information fields
				echo "<td>" . $row['firstName'] . "</td>";
				echo "<td>" . $row['lastName'] . "</td>";
				echo "<td>" . $row['birthDate'] . "</td>";
				echo "<td>" . genderDecoderShort($row['gender']) . "</td>";
				
				// Display 'isHeadOfHousehold' as a Checkmark or blank
				$head = ($row['isHeadOfHousehold'] ? "&#10004;" : ""); 
				
				echo "<td style='color:green;'>$head</td>";

				//echo "<td>" . $row['notes'] . "</td>";
				
				// Delete button
				if ($showDeleteColumn) {
					echo "<td>";
          echo "<button id='InactiveMember' name='DeleteMember' class='btn-icon' ";
					if (!$row['isHeadOfHousehold']) {
						echo "type='submit' onclick=\"javascript: return confirm('Are you sure you want to remove this family member?');\")'>";	
					}
					else {
						echo "type='button' onclick=\"javascript: alert('Cannot delete the head of household.');\")'>";
					}
          echo "<i class='fa fa-trash'></i></button></td>";
				}
				
				// Close off the row and form
				echo "</tr></form>";
			}
			echo "</table>";
			
			echo "<br><form action='ap_co4.php'>";
			
			// Send client ID so we know which family this belongs to
			echo "<input type='hidden' name='id' value=" . $_GET['id'] . ">";
			
			// Send along the client last name so we can autofill the last name
			echo "<input type='hidden' name='lnamedefault' value='" . $clientName . "'>";
			
			echo "<input class='btn-nav' id='newMember' type='submit' name='newMember' value='New Family Member'>";	
			echo "</form>";
			
			// ***********************************************************************
			// SHOW VISITS
			// Show all of the visits (actionable to view further information)
			// TODO Add a button to add an appointment
			?>
			<hr><br><h3>Appointments</h3>
			<table width='95%' id="invoiceTable" class="display">
				<thead>
					<tr>
						<th width='5%'></th>
						<th>Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>				
			<?php
		} 
		else {
			echo "Client was not found.";
		}	
	?>
	
<?php include 'php/footer.php'; ?>
<script src="js/clientOps.js"></script>
<script type="text/javascript">
  if (getCookie("newClient") != "") {
    window.alert("New Client Added!");
    removeCookie("newClient");
  }
  if (getCookie("DelFam") != "") {
    window.alert("Family Member Removed!");
    removeCookie("DelFam");
  }
  if (getCookie("Err_DelFam") != "") {
    window.alert("Cannot Remove The Head of Household!");
    removeCookie("Err_DelFam");
  }
    
	<?php
		echo "var Params = '?cid=" . $_GET['id'] . "';";
	?>
	$('#invoiceTable').DataTable({
      "searching"     : false,
      "ordering"      : false,
	  "language"	  : {
		"emptyTable"  : "No Appointments in Database."
					    },
      "ajax"	      : {
          "url"       : "php/ajax/clientApptList.php" + Params,
						},
	});
	$(document).ready(function(){
		$('#invoiceTable').on('click', '.btn-edit', function () {
			window.location.assign($(this).attr('value'));
		});
	});
</script>
