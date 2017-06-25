<?php

include 'utilities.php';

if(isset($_POST['createDonation'])) /*when the button is pressed on post request*/
{
    

    $pickupDate = $_POST['pickupDate'];
    $networkPartner = $_POST['networkPartner']; 
    $agency = $_POST['agency'];
    $donorName = $_POST['donorName'];
    $city = $_POST['city'];

    $frozenNonMeat = $_POST['frozenNonMeat'];
    $frozenMeat = $_POST['frozenMeat'];
    $frozenPrepared = $_POST['frozenPrepared'];
    $refBakery = $_POST['refBakery'];
    $refProduce = $_POST['refProduce'];
    $refDairyAndDeli = $_POST['refDairyAndDeli'];
    $dryShelfStable = $_POST['dryShelfStable'];
    $dryNonFood = $_POST['dryNonFood'];
    $dryFoodDrive = $_POST['dryFoodDrive'];



    

    $donorID = null;




    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


    //check to see if category exists, if not create it.
        $result = $conn->query("SELECT DISTINCT name, city FROM DonationPartner WHERE name = '$donorName' && city = '$city'");
        if($result->num_rows == 0) {
        
         $sql = "INSERT INTO DonationPartner (name, city, state, zip, address, phoneNumber)
         VALUES ('$donorName', '$city', '', '', '', '')";
            if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "New donation partner created: $donorName in $city", "green");
                
                
                $sql = "SELECT DISTINCT name, donationPartnerID, city FROM DonationPartner WHERE name = '$donorName' && city = '$city'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $donorID = $row["donationPartnerID"];
                        }
                    echoDivWithColor("Donor ID: $donorID", "green" );
                } 
          }
          else{
            echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
            echoDivWithColor("Error, failed to connect to database at donation insert $sql $conn->error", "red" );
          }

                  
          

    } else {
        $sql = "SELECT DISTINCT name, donationPartnerID, city FROM DonationPartner WHERE name = '$donorName' && city = '$city'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $donorID = $row["donationPartnerID"];
                }
                
            }
       echoDivWithColor( "$donorName in $city already in database", "green");
       echoDivWithColor("Category ID: $donorID", "green" );
    }


    $sql = "INSERT INTO Donation (donationPartnerID, dateOfPickup, networkPartner, agency, frozenNonMeat, frozenMeat, frozenPrepared, refBakery, refProduce, refDairyAndDeli, dryShelfStable, dryNonFood, dryFoodDrive)
       VALUES ('$donorID', '$pickupDate', '$networkPartner', '$agency', '$frozenNonMeat', '$frozenMeat', '$frozenPrepared', '$refBakery', '$refProduce', '$refDairyAndDeli', '$dryShelfStable', '$dryNonFood', '$dryFoodDrive')"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Donation created successfully", "green" );
        echoDivWithColor("Date of pickup: $pickupDate", "green" );
        echoDivWithColor("Network partner: $networkPartner", "green" );
        echoDivWithColor("Agency: $agency", "green" );
        echoDivWithColor("Box quantites of the following:", "black" );
        echoDivWithColor("Frozen assorted food (Non meat): $frozenNonMeat", "green" );
        echoDivWithColor("Frozen assorted meat and seafood: $frozenMeat", "green" );
        echoDivWithColor("Frozen assorted prepared foods: $frozenPrepared", "green" );
        echoDivWithColor("Refridgerated assorted bakery and pastries: $refBakery", "green" );
        echoDivWithColor("Refridgerated assorted produce: $refProduce", "green" );
        echoDivWithColor("Refridgerated assorted dairy and deli foods: $refDairyAndDeli", "green" );
        echoDivWithColor("Assorted foods (Shelf-stable): $dryShelfStable", "green" );
        echoDivWithColor("Assorted non-food products: $dryNonFood", "green" );
        echoDivWithColor("Assorted food drive foods: $dryFoodDrive", "green" );

        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at donation insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif(isset($_POST['createDonationPartner'])) /*when the button is pressed on post request*/
{
    

    $name = $_POST['name'];
    $state = $_POST['state']; 
    $zip = $_POST['zip'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $phoneNumber = $_POST['phoneNumber'];




    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


  


    $sql = "INSERT INTO DonationPartner (name, city, state, zip, address, phoneNumber)
       VALUES ('$name', '$city', '$state', '$zip', '$address', '$phoneNumber')"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Donation partner created successfully", "green" );
        echoDivWithColor("Partner name: $name", "green" );
        echoDivWithColor("City: $city", "green" );
        echoDivWithColor("State: $state", "green" );
        echoDivWithColor("Zip: $zip", "green" );
        echoDivWithColor("Address: $address", "green" );
        echoDivWithColor("Phone number: $phoneNumber", "green" );
      

        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at donation partner insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif (isset($_GET['UpdateItem'])) {
	header ("location: /RUMCPantry/ap_io3.html?itemID=" . $_GET['itemID']);
}
elseif (isset($_GET['DeleteItem'])) {

    $conn = createPantryDatabaseConnection();
    $itemID = $_GET['itemID'];
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 



    
    $result = $conn->query("SELECT DISTINCT itemID FROM Item WHERE itemID = '$itemID'");
    if($result->num_rows > 0) {

        $sql = "update Item set isDeleted=true where itemID=$itemID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Item with item id $itemID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, failed to connect to database at delete." . $conn->connect_error, "red" );
          }
    }
   
}
elseif (isset($_GET['updateDonation'])) {
	header ("location: /RUMCPantry/ap_do4.html?donationID=" . $_GET['donationID']);
}
elseif (isset($_GET['deleteDonation'])) {
	$conn = createPantryDatabaseConnection();
    $donationID = $_GET['donationID'];
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 



    
    $result = $conn->query("SELECT DISTINCT donationID FROM Donation WHERE donationID = '$donationID'");
    if($result->num_rows > 0) {

        $sql = "delete from Donation where donationID=$donationID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Donation with Donation id $donationID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, failed to connect to database at delete." . $conn->connect_error, "red" );
          }
    }
}
elseif (isset($_GET['updateDonationPartner'])) {
	header ("location: /RUMCPantry/ap_do5.html?donationPartnerID=" . $_GET['donationPartnerID']);
}
elseif (isset($_GET['deleteDonationPartner'])) {
	$conn = createPantryDatabaseConnection();
    $donationPartnerID = $_GET['donationPartnerID'];
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 



    
    $result = $conn->query("SELECT DISTINCT donationPartnerID FROM DonationPartner WHERE donationPartnerID = '$donationPartnerID'");
    if($result->num_rows > 0) {

        $sql = "delete from DonationPartner where donationPartnerID=$donationPartnerID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Donation Partner with donationPartnerID id $donationPartnerID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, this donation partner is in use" . $conn->connect_error, "red" );
          }
    }
}
elseif (isset($_GET['updateDonationIndividual'])) {
}
elseif (isset($_GET['updateDonationPartnerIndividual'])) {
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>