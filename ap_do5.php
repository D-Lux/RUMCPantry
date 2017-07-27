<!DOCTYPE html>

<html>

<head>
    <title>Roselle United Methodist Church Food Pantry</title>
    <script src="js/utilities.js"></script>
    <script src="js/createDonation.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <!--<link href='style.css' rel='stylesheet'>-->
    <?php include 'php/checkLogin.php';?>


</head>

<body>
    <h1>Roselle United Methodist Church</h1>
    <h2>Food Pantry</h2>
    <h3>Admin Page Inventory Ops 3</h3>


    <button onclick="goBack()">Back</button>

    <?php
    include 'php/utilities.php';
    echo "<h3> Update donation partner number: ". $_GET['donationPartnerID'] . "</h3>";
   
 
    $donationPartnerID = $_GET['donationPartnerID'];
    $name ="";
    $city ="";
    $state="";
    $zip="";
    $address="";
    $phoneNumber="";

    

     /* Create connection*/
 	$conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT donationPartnerID, name, city, state, zip, address, phoneNumber FROM DonationPartner WHERE donationPartnerID =". $_GET['donationPartnerID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $donationPartnerID = $row["donationPartnerID"];
                
                $name= $row["name"];
                $city= $row["city"];
                $state= $row["state"];
                $zip= $row["zip"];
                $address= $row["address"];
                $phoneNumber= $row["phoneNumber"];
              

        }
    }
    else
    {
        echoDivWithColor("<h1><b><i>Item does not exist!</h1></b></i>","red");
    }

    echo'<form name="addDonationPartner" action="php/donationOps.php" onSubmit="return validateDonationPartnerAdd()" method="post">
        <input type="hidden" name="donationPartnerID" value=' . $donationPartnerID . '>
        <div id="name">
            Donation partner name:<span style="color:red;">*</span>';
     
            createDatalist("$name","names","DonationPartner","name","name", false);
        
        echo'</div>
        <div id="city">
            City:<span style="color:red;">*</span>';
            
            createDatalist("$city","cities","DonationPartner","city","city", false);
        
        echo'</div>
        <div id="state">
            State:
            
            <select name="state">';
               
                echo"<option value='AL' " . ("AL" == $state ? "selected" : "") . ">AL</option>"; 
                echo"<option value='AK' " . ("AK" == $state ? "selected" : "") . ">AK</option>"; 
                echo"<option value='AZ' " . ("AZ" == $state ? "selected" : "") . ">AZ</option>"; 
                echo"<option value='AR' " . ("AR" == $state ? "selected" : "") . ">AR</option>"; 
                echo"<option value='CA' " . ("CA" == $state ? "selected" : "") . ">CA</option>"; 
                echo"<option value='CO' " . ("CO" == $state ? "selected" : "") . ">CO</option>"; 
                echo"<option value='CT' " . ("CT" == $state ? "selected" : "") . ">CT</option>"; 
                echo"<option value='DE' " . ("DE" == $state ? "selected" : "") . ">DE</option>"; 
                echo"<option value='DC' " . ("DC" == $state ? "selected" : "") . ">DC</option>"; 
                echo"<option value='FL' " . ("FL" == $state ? "selected" : "") . ">FL</option>"; 
                echo"<option value='GA' " . ("GA" == $state ? "selected" : "") . ">GA</option>"; 
                echo"<option value='HI' " . ("HI" == $state ? "selected" : "") . ">HI</option>"; 
                echo"<option value='ID' " . ("ID" == $state ? "selected" : "") . ">ID</option>"; 
                echo"<option value='IL' " . ("IL" == $state ? "selected" : "") . ">IL</option>"; 
                echo"<option value='IN' " . ("IN" == $state ? "selected" : "") . ">IN</option>"; 
                echo"<option value='IA' " . ("IA" == $state ? "selected" : "") . ">IA</option>"; 
                echo"<option value='KS' " . ("KS" == $state ? "selected" : "") . ">KS</option>"; 
                echo"<option value='KY' " . ("KY" == $state ? "selected" : "") . ">KY</option>"; 
                echo"<option value='LA' " . ("LA" == $state ? "selected" : "") . ">LA</option>"; 
                echo"<option value='ME' " . ("ME" == $state ? "selected" : "") . ">ME</option>"; 
                echo"<option value='MD' " . ("MD" == $state ? "selected" : "") . ">MD</option>"; 
                echo"<option value='MA' " . ("MA" == $state ? "selected" : "") . ">MA</option>"; 
                echo"<option value='MI' " . ("MI" == $state ? "selected" : "") . ">MI</option>"; 
                echo"<option value='MN' " . ("MN" == $state ? "selected" : "") . ">MN</option>"; 
                echo"<option value='MS' " . ("MS" == $state ? "selected" : "") . ">MS</option>"; 
                echo"<option value='MO' " . ("MO" == $state ? "selected" : "") . ">MO</option>"; 
                echo"<option value='MT' " . ("MT" == $state ? "selected" : "") . ">MT</option>"; 
                echo"<option value='NE' " . ("NE" == $state ? "selected" : "") . ">NE</option>"; 
                echo"<option value='NV' " . ("NV" == $state ? "selected" : "") . ">NV</option>"; 
                echo"<option value='NH' " . ("NH" == $state ? "selected" : "") . ">NH</option>"; 
                echo"<option value='NJ' " . ("NJ" == $state ? "selected" : "") . ">NJ</option>"; 
                echo"<option value='NM' " . ("NM" == $state ? "selected" : "") . ">NM</option>"; 
                echo"<option value='NY' " . ("NY" == $state ? "selected" : "") . ">NY</option>"; 
                echo"<option value='NC' " . ("NC" == $state ? "selected" : "") . ">NC</option>"; 
                echo"<option value='ND' " . ("ND" == $state ? "selected" : "") . ">ND</option>"; 
                echo"<option value='OH' " . ("OH" == $state ? "selected" : "") . ">OH</option>"; 
                echo"<option value='OK' " . ("OK" == $state ? "selected" : "") . ">OK</option>"; 
                echo"<option value='OR' " . ("OR" == $state ? "selected" : "") . ">OR</option>"; 
                echo"<option value='PA' " . ("PA" == $state ? "selected" : "") . ">PA</option>"; 
                echo"<option value='RI' " . ("RI" == $state ? "selected" : "") . ">RI</option>"; 
                echo"<option value='SC' " . ("SC" == $state ? "selected" : "") . ">SC</option>"; 
                echo"<option value='SD' " . ("SD" == $state ? "selected" : "") . ">SD</option>"; 
                echo"<option value='TN' " . ("TN" == $state ? "selected" : "") . ">TN</option>"; 
                echo"<option value='TX' " . ("TX" == $state ? "selected" : "") . ">TX</option>"; 
                echo"<option value='UT' " . ("UT" == $state ? "selected" : "") . ">UT</option>"; 
                echo"<option value='VT' " . ("VT" == $state ? "selected" : "") . ">VT</option>"; 
                echo"<option value='VA' " . ("VA" == $state ? "selected" : "") . ">VA</option>"; 
                echo"<option value='WA' " . ("WA" == $state ? "selected" : "") . ">WA</option>"; 
                echo"<option value='WV' " . ("WV" == $state ? "selected" : "") . ">WV</option>"; 
                echo"<option value='WI' " . ("WI" == $state ? "selected" : "") . ">WI</option>"; 
                echo"<option value='WY' " . ("WY" == $state ? "selected" : "") . ">WY</option>";
                echo'</select>
        </div>
        <div id="zip">
            Zip:<span style="color:red;">*</span>';
            
            
            createDatalist("$zip","zips","DonationPartner","zip","zip", false);
            
        echo'</div>
        <div id="address">
            Address:<span style="color:red;">*</span>';
            
            
            createDatalist("$address","addresses","DonationPartner","address","address", false);
        
        echo'</div>
        <div id="phoneNumber">
            Phone number:<span style="color:red;">*</span>';
            
            
            createDatalist("$phoneNumber","phoneNumbers","DonationPartner","phoneNumber","phoneNumber", false);
        
        echo'</div>

        <input type="submit" value="Update donation partner" name="updateDonationPartnerIndividual">
        </form>';
        ?>

</body>

</html>