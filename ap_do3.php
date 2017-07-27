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
    <h3>Admin Page Donation Ops3: Add a donation partner</h3>


    <button onclick="goBack()">Back</button>
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
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option selected="selected" value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>
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



</body>

</html>