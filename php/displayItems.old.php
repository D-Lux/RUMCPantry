 <?php

 include 'itemOps.php';


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



$sql = "SELECT isDeleted, itemID, itemName, displayName, price, small, medium, large, categoryID, aisle, rack, shelf FROM item";
$result = $conn->query($sql);
$hasDeleted =0;
    $hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
    

    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Category Name</th><th>Delete</th></tr>";
    while($row = $result->fetch_assoc()) {
        $categoryName ="";
        if($row["isDeleted"] == false)
            {
            $sql1 = "SELECT DISTINCT name, categoryID FROM Category WHERE categoryID = ". $row['categoryID'];
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    $categoryName = $row1["name"];
                }
            }

            if($categoryName != "redistribution")
            {
                
                echo "<tr>";
                //grab item id
                echo "<form action=''>";
                $itemID=$row["itemID"];
                echo "<input type='hidden' name='itemID' value='$itemID'>";
                echo "<td><input type='submit' name='UpdateItem' value='Edit'></td>";
                echo "<td>". $row["itemName"]. "</td><td>" . $row["aisle"] . "</td><td>" . $row["rack"] . "</td><td>" . $row["shelf"] .  "</td><td>$categoryName</td>";
                echo "<td><input type='submit' name='DeleteItem'  class = 'btn_trash' value=' '></td>";
                echo "</form>";
                echo "</tr>";
                $hasReal++;
            }
            }
        else
        {
            $hasDeleted++;
        }
            
    }
   echo "</table>";

   if($hasDeleted > 0 && $hasReal == 0)
   {
        echo "<div>There is currently nothing in the items table</div>";
   }
   
   } else {
    echo "<div>There is currently nothing in the items table</div>";
}

$conn->close();
 
 
 ?>