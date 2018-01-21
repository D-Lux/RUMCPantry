<?php
  $pageRestriction = 99;
  include 'php/header.php';
  include 'php/backButton.php';
?>
    
    <div class="body-content">
    <?php  
    //echo "<h3> Update donation partner number: ". $_GET['donationPartnerID'] . "</h3>";
   
 
    $donationPartnerID = $_GET['donationPartnerID'];
    $name ="";
    $city ="";
    $state="";
    $zip="";
    $address="";
    $phoneNumber="";

    

     /* Create connection*/
    $conn = connectDB();
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
      
      // TODO: Remove this
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
        
        echo "</div>";
        echo "<div id='state'>State:<select name='state'>";
        getStateOptions($state);
        echo'</select></div>
        
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

        <input type="submit" value="Update" name="updateDonationPartnerIndividual">
        </form>';

        $sql = "SELECT donationID, donationPartnerID, dateOfPickup, networkPartner, agency, frozenNonMeat, frozenMeat, frozenPrepared, refBakery, refProduce, refDairyAndDeli, dryShelfStable, dryNonFood, dryFoodDrive FROM Donation WHERE donationPartnerID = '$donationPartnerID' ";
        $result = $conn->query($sql);
        $hasReal =0;
        if ($result->num_rows > 0) {
            
            // output data of each row
          
           
        
            
            echo "<table>";
            echo "<tr><th>Edit</th><th>Donation Partner Name</th><th>Date of Pickup</th><th>Network Partner</th><th>Agency</th><th>Delete</th></tr>";
            while($row = $result->fetch_assoc()) {
                $donationParnterName ="";
        
                $sql1 = "SELECT DISTINCT name, donationPartnerID 
						  FROM DonationPartner WHERE donationPartnerID = ". $row['donationPartnerID'];
                $result1 = $conn->query($sql1);
                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $donationParnterName = $row1["name"];
                    }
                }
                echo "<tr>";
                //grab donation id
                echo "<form action='' method='get'>";
                $donationID=$row["donationID"];
                echo "<input type='hidden' name='donationID' value='$donationID'>";
				echo "<input type='hiddne' name='donationPartnerID' value='$donationPartnerID'>";
                echo "<td><input type='submit' name='updateDonation' value='Edit'></td>";
                echo "<td>$donationParnterName</td><td>". $row["dateOfPickup"]. "</td><td>" . $row["networkPartner"] . "</td><td>" . $row["agency"] . "</td>";
                echo "<td><input type='submit' name='deleteDonation' class = 'btn_trash' value=' '></td>";
                echo "</form>";
                echo "</tr>";
                $hasReal++;
                }
               
                    
            }
           echo "</table>";
        
           if($hasReal == 0)
           {
                echo "<div>There is currently nothing in the donations table</div>";
           }
           
           
        $conn->close();
        ?>

<?php include 'php/footer.php'; ?>
<script src="js/createDonation.js"></script>
