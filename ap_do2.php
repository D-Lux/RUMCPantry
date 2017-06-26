<!DOCTYPE html>

<html>

<head>
    <title>Roselle United Methodist Church Food Pantry</title>
    <script src="js/utilities.js"></script>
    <script src="js/createDonation.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <?php include 'php/utilities.php'; ?>
    <?php include 'php/checkLogin.php';?>



</head>

<body>
    <h1>Roselle United Methodist Church</h1>
    <h2>Food Pantry</h2>
    <h3>Admin Page Donation Ops2: Add a donation</h3>

    <button onclick="goBack()">Back</button>

    <form name="addDonation" action="php/donationOps.php" onSubmit="return validateDonationAdd()" method="post">
        <!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
        <!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->
        <div id="pickupDate">
            Pickup date:
            <input type="date" name="pickupDate">
        </div>
        <div id="networkPartner">
            Network partner:
            <?php 
            createDatalist("RUMC","networkPartners","Donation","networkPartner","networkPartner", false);
            ?>
        </div>
        <div id="agency">
            Agency:
            <?php 
            
            createDatalist("1039a","agencies","Donation","agency","agency", false);
            ?>
        </div>
        <div id="donorName">
            Donor name:
            <?php 
            
            createDatalist("","donorNames","DonationPartner","name","donorName", false);
            ?>
        </div>
        <div id="city">
            City:
            <?php 
            
            createDatalist("","cities","DonationPartner","city","city", false);
            ?>
        </div>
        <h3>
            Box Quantities of the following:
        </h3>
        <div id="frozenNonMeat">Frozen assorted food (Non meat): <input type="number" min="0" max="100000" value="0" step="1" name="frozenNonMeat" /></div>
        <div id="frozenMeat">Frozen assorted meat and seafood: <input type="number" min="0" max="100000" value="0" step="1" name="frozenMeat" /></div>
        <div id="frozenPrepared">Frozen assorted prepared foods: <input type="number" min="0" max="100000" value="0" step="1" name="frozenPrepared" /></div>
        <div id="refBakery">Refridgerated assorted bakery and pastries: <input type="number" min="0" max="100000" value="0" step="1" name="refBakery" /></div>
        <div id="refProduce">Refridgerated assorted produce: <input type="number" min="0" max="100000" value="0" step="1" name="refProduce" /></div>
        <div id="refDairyAndDeli">Refridgerated assorted dairy and deli foods: <input type="number" min="0" max="100000" value="0" step="1" name="refDairyAndDeli" /></div>
        <div id="dryShelfStable">Assorted foods (Shelf-stable): <input type="number" min="0" max="100000" value="0" step="1" name="dryShelfStable" /></div>
        <div id="dryNonFood">Assorted non-food products: <input type="number" min="0" max="100000" value="0" step="1" name="dryNonFood" /></div>
        <div id="dryFoodDrive">Assorted food drive foods: <input type="number" min="0" max="100000" value="0" step="1" name="dryFoodDrive" /></div>
        </br>
        <input type="submit" value="Create" name="createDonation">
    </form>

</body>

</html>