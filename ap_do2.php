<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
    <h3>Donation Operations: Add a donation</h3>
    <div class="body-content">

    <form name="addDonation" action="php/donationOps.php" onSubmit="return validateDonationAdd()" method="post">
        <!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
        <!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->
        
		<div id="pickupDate">
            Pickup date<span style="color:red;">*</span>
            <input type="date" name="pickupDate" <?php echo "value='" . (date('Y-m-d')) . "'" ?> >
        </div>
		
		<div id="networkPartner">
            Network partner:<span style="color:red;">*</span>
            <?php 
            createDatalist("RUMC","networkPartners","Donation","networkPartner","networkPartner", false);
            ?>
        </div>
		<div id="agency">
            Agency:<span style="color:red;">*</span>
            <?php 
            
            createDatalist("1039a","agencies","Donation","agency","agency", false);
            ?>
        </div>
	
		<div id="City">
            City:<span style="color:red;">*</span>
            <?php
            createDatalist_i("","cities","donationpartner","city","city", false);
            ?>
        </div>
		<div id="donorName">
            Donor name:<span style="color:red;">*</span>
            <?php 
            
            createDatalist_i("","donorNames","DonationPartner","name","donorName", false);
            ?>
        </div>
	
		
		
		
        <h3>
            Box Quantities of the following:
        </h3>
        <div id="frozenNonMeat">Frozen assorted food (Non meat): <input type="number" min="0" max="100000" value="0" step="1" name="frozenNonMeat" ></div>
        <div id="frozenMeat">Frozen assorted meat and seafood: <input type="number" min="0" max="100000" value="0" step="1" name="frozenMeat" ></div>
        <div id="frozenPrepared">Frozen assorted prepared foods: <input type="number" min="0" max="100000" value="0" step="1" name="frozenPrepared" ></div>
        <div id="refBakery">Refridgerated assorted bakery and pastries: <input type="number" min="0" max="100000" value="0" step="1" name="refBakery" ></div>
        <div id="refProduce">Refridgerated assorted produce: <input type="number" min="0" max="100000" value="0" step="1" name="refProduce" ></div>
        <div id="refDairyAndDeli">Refridgerated assorted dairy and deli foods: <input type="number" min="0" max="100000" value="0" step="1" name="refDairyAndDeli" ></div>
        <div id="dryShelfStable">Assorted foods (Shelf-stable): <input type="number" min="0" max="100000" value="0" step="1" name="dryShelfStable" ></div>
        <div id="dryNonFood">Assorted non-food products: <input type="number" min="0" max="100000" value="0" step="1" name="dryNonFood" ></div>
        <div id="dryFoodDrive">Assorted food drive foods: <input type="number" min="0" max="100000" value="0" step="1" name="dryFoodDrive" ></div>
        </br>
        <input type="submit" value="Create" name="createDonation">
    </form>

    
<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>