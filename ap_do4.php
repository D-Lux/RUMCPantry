<?php 
  include 'php/header.php';
  include 'php/backButton.php';
?>
    
    <?php
    echo "<h3> Update donation number: ". $_GET['donationID'] . "</h3>";
    echo "<div class='body_content'>";
 
    $donationID = $_GET['donationID'];
    $donationPartnerID ="";
    $donationPartnerName ="";
    $city="";
    $dateOfPickup="";
    $networkPartner="";
    $agency="";
    $frozenNonMeat=0;
    $frozenMeat=0;
    $frozenPrepared=0;
    $refBakery=0;
    $refProduce=0;
    $refDairyAndDeli=0;
    $dryShelfStable=0;
    $dryNonFood=0;
    $dryFoodDrive=0;
    

     /* Create connection*/
 	$conn = connectDB();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT donationID, donationPartnerID, dateOfPickup, networkPartner, agency, frozenNonMeat, frozenMeat, frozenPrepared, refBakery, refDairyAndDeli, refProduce, dryFoodDrive, dryNonFood, dryShelfStable FROM Donation WHERE donationID =". $_GET['donationID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $donationPartnerID = $row["donationPartnerID"];
                $donationID = $row["donationID"];
                $dateOfPickup= $row["dateOfPickup"];
                $networkPartner= $row["networkPartner"];
                $agency= $row["agency"];
                $frozenNonMeat= $row["frozenNonMeat"];
                $frozenMeat= $row["frozenMeat"];
                $frozenPrepared= $row["frozenPrepared"];
                $refBakery= $row["refBakery"];
                $refProduce= $row["refProduce"];
                $refDairyAndDeli= $row["refDairyAndDeli"];
                $dryShelfStable= $row["dryShelfStable"];
                $dryNonFood= $row["dryNonFood"];
                $dryFoodDrive= $row["dryFoodDrive"];
                     
                            
                    $sql = "SELECT DISTINCT city, name, donationPartnerID FROM DonationPartner WHERE donationPartnerID = '$donationPartnerID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $donationPartnerName = $row["name"];
                                $city = $row["city"];
                            }
                    } 


        }
    }
    else
    {
      // TODO remove this
        echoDivWithColor("<h1><b><i>Item does not exist!</h1></b></i>","red");
    }

    echo'<form name="addDonation" action="php/donationOps.php" onSubmit="return validateDonationAdd()" method="post">';

   



    echo'<div id="pickupDate">Pickup date:<span style="color:red;">*</span>
    <input type="date" name="pickupDate" value=' . $dateOfPickup . '>
    </div>
    <div id ="donationID">
        <input type="hidden" name="donationID" value=' . $donationID . '>
    </div>
    <div id="networkPartner">
        Network partner:<span style="color:red;">*</span>';
        
        createDatalist("$networkPartner","networkPartners","Donation","networkPartner","networkPartner", false);
        
    echo'</div>
    <div id="agency">
        Agency:<span style="color:red;">*</span>';
            
        
        createDatalist("$agency","agencies","Donation","agency","agency", false);
    
    echo'</div>
    <div id="donorName">
        Donor name:<span style="color:red;">*</span>';
        
    
        createDatalist("$donationPartnerName","donorNames","DonationPartner","name","donorName", false);

        echo'</div>
        <div id="city">
            City:<span style="color:red;">*</span>';
            
    
        createDatalist("$city","cities","DonationPartner","city","city", false);

        echo'</div>
        <h3>
            Box Quantities of the following:
        </h3>
        <div id="frozenNonMeat">Frozen assorted food (Non meat): <input type="number" min="0" max="100000" value=' . $frozenNonMeat . ' step="1" name="frozenNonMeat" /></div>
        <div id="frozenMeat">Frozen assorted meat and seafood: <input type="number" min="0" max="100000" value=' . $frozenMeat . ' step="1" name="frozenMeat" /></div>
        <div id="frozenPrepared">Frozen assorted prepared foods: <input type="number" min="0" max="100000" value=' . $frozenPrepared . ' step="1" name="frozenPrepared" /></div>
        <div id="refBakery">Refridgerated assorted bakery and pastries: <input type="number" min="0" max="100000" value=' . $refBakery . ' step="1" name="refBakery" /></div>
        <div id="refProduce">Refridgerated assorted produce: <input type="number" min="0" max="100000" value=' . $refProduce . ' step="1" name="refProduce" /></div>
        <div id="refDairyAndDeli">Refridgerated assorted dairy and deli foods: <input type="number" min="0" max="100000" value=' . $refDairyAndDeli . ' step="1" name="refDairyAndDeli" /></div>
        <div id="dryShelfStable">Assorted foods (Shelf-stable): <input type="number" min="0" max="100000" value=' . $dryShelfStable . ' step="1" name="dryShelfStable" /></div>
        <div id="dryNonFood">Assorted non-food products: <input type="number" min="0" max="100000" value=' . $dryNonFood . ' step="1" name="dryNonFood" /></div>
        <div id="dryFoodDrive">Assorted food drive foods: <input type="number" min="0" max="100000" value=' . $dryFoodDrive . ' step="1" name="dryFoodDrive" /></div>
        </br>

        <input type="submit" value="Update" name="updateDonationIndividual"> 
        </form>'; ?>
        
<?php include 'php/footer.php'; ?>        
<script src="js/createDonation.js"></script>
