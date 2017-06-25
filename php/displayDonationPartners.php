 <?php




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
$sql = "SELECT donationPartnerID, name, city, state, zip, address, phoneNumber FROM DonationPartner";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    // output data of each row
  
    $hasReal =0;

    
    echo "<table>";
    echo "<tr><th>Update</th><th>Donation Partner ID</th><th>Name</th><th>City</th><th>State</th><th>Zip</th><th>Address</th><th>phoneNumber</th>";
    while($row = $result->fetch_assoc()) {
        
        echo "<tr>";
        //grab donation id
        echo "<form action=''>";
        $donationPartnerID=$row["donationPartnerID"];
        echo "<input type='hidden' name='donationPartnerID' value='$donationPartnerID'>";
        echo "<td><input type='submit' name='UpdateDonationPartner' value='Update'></td>";
        echo "<td>". $row["donationPartnerID"]. "</td><td>". $row["name"]. "</td><td>" . $row["city"] . "</td><td>" . $row["state"] . "</td><td>" . $row["zip"] . "</td><td>" . $row["address"] . "</td><td>" . $row["phoneNumber"] . "</td>";
        echo "<td><input type='submit' name='DeleteDonationPartner' value='Delete'></td>";
        echo "</form>";
        echo "</tr>";
        $hasReal++;
        }
       
            
    }
   echo "</table>";

   if($hasReal == 0)
   {
        echo "There is currently nothing in the donation partners table";
   }
   
   
$conn->close();
 
 
 ?>