<?php 
  include 'php/header.php';
  include 'php/backButton.php';
?>
    <h3>Donation Operations: Add a donation partner</h3>
    <div class="body_content">

	<form name="createDonationPartner" action="php/donationOps.php" onSubmit="return validateDonationPartnerAdd()" method="post">
        <!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
        <!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->

        <div id="name">
            Donation partner name:<span style="color:red;">*</span>
            <?php 
            createDatalist("","names","DonationPartner","name","name", false);
            ?>
        </div>
        <div id="city">
            City:<span style="color:red;">*</span>
            <?php 
            createDatalist("","cities","DonationPartner","city","city", false);
            ?>
        </div>
        <div id="state">
            State:<span style="color:red;">*</span>
            <select name="state">
              <?=getStateOptions('IL')?>
            </select>
        </div>
        <div id="zip">
            Zip:<span style="color:red;">*</span>
            <?php 
            
            createDatalist("","zips","DonationPartner","zip","zip", false);
            ?>
        </div>
        <div id="address">
            Address:<span style="color:red;">*</span>
            <?php 
            
            createDatalist("","addresses","DonationPartner","address","address", false);
            ?>
        </div>
        <div id="phoneNumber">
            Phone number:<span style="color:red;">*</span>
            <?php 
            
            createDatalist("","phoneNumbers","DonationPartner","phoneNumber","phoneNumber", false);
            ?>
        </div>

        <input type="submit" value="Create donation partner" name="createDonationPartner">
    </form>

<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>