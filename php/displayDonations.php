 <?php

 include 'donationOps.php';


    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";
    $categoryName="";


$conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

    
} 



 //TODO currently I have it pulling the donationpartner ID, need to pull the donation partner name
$sql = "SELECT donationID, donationPartnerID, dateOfPickup, networkPartner, agency, frozenNonMeat, frozenMeat, frozenPrepared, refBakery, refProduce, refDairyAndDeli, dryShelfStable, dryNonFood, dryFoodDrive FROM Donation";
$result = $conn->query($sql);
$hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
  
   

    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Donation ID</th><th>Donation Partner Name</th><th>Date of Pickup</th><th>Network Partner</th><th>Agency</th><th>Frozen Non Meat</th><th>Frozen Meat</th><th>Frozen Prepared</th><th>Refridgerated Bakery</th><th>Refridgerated Produce</th><th>Refridgerated Dairy and Deli</th><th>Dry Shelf Stable</th><th>Dry Non Food</th><th>Dry Food Drive</th><th>Delete</th></tr>";
    while($row = $result->fetch_assoc()) {
        $donationParnterName ="";

        $sql1 = "SELECT DISTINCT name, donationPartnerID FROM DonationPartner WHERE donationPartnerID = ". $row['donationPartnerID'];
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            while($row1 = $result1->fetch_assoc()) {
                $donationParnterName = $row1["name"];
            }
        }
        echo "<tr>";
        //grab donation id
        echo "<form action=''>";
        $donationID=$row["donationID"];
        echo "<input type='hidden' name='donationID' value='$donationID'>";
        echo "<td><input type='submit' name='updateDonation' value='Edit'></td>";
        echo "<td>". $row["donationID"]. "</td><td>$donationParnterName</td><td>". $row["dateOfPickup"]. "</td><td>" . $row["networkPartner"] . "</td><td>" . $row["agency"] . "</td><td>" . $row["frozenNonMeat"] . "</td><td>" . $row["frozenMeat"] . "</td><td>" . $row["frozenPrepared"] . "</td><td>" . $row["refBakery"] . "</td><td>" . $row["refProduce"] . "</td><td>" . $row["refDairyAndDeli"] . "</td><td>". $row["dryShelfStable"]. "</td><td>". $row["dryNonFood"]. "</td><td>". $row["dryFoodDrive"]. "</td>";
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