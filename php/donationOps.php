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
elseif (isset($_GET['UpdateItem'])) {
	header ("location: /RUMCPantry/ap_io3.html?itemID=" . $_GET['itemID']);
}
elseif (isset($_GET['DeleteItem'])) {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";
    $itemID = $_GET['itemID'];

     $conn = new mysqli($servername, $username, $password, $dbname);
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
            echoDivWithColor("Error, failed to connect to database at delete.", "red" );
          }
    }
   
}
elseif (isset($_GET['updateItem'])) {
	
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>