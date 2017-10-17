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



 //TODO currently I have it pulling the category ID, need to pull the category name
$sql = "SELECT isDeleted, itemID, itemName, displayName, price, small, medium, large, categoryID FROM item";
$result = $conn->query($sql);
$hasDeleted =0;
    $hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
    

    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Item ID</th><th>Item Name</th><th>Display Name</th><th>Price</th><th>Small</th><th>Medium</th><th>Large</th><th>Category ID</th><th>Category Name</th><th>Reactivate</th></tr>";
    while($row = $result->fetch_assoc()) {
        $categoryName ="";
        if($row["isDeleted"] == true)
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
                    echo "<input type='hidden' name='categoryName' value='$categoryName'>";
                    echo "<td><input type='submit' name='UpdateItem' value='Edit'></td>";
                    echo "<td>". $row["itemID"]. "</td><td>". $row["itemName"]. "</td><td>" . $row["displayName"] . "</td><td>" . $row["price"] . "</td><td>" . $row["small"] . "</td><td>" . $row["medium"] . "</td><td>" . $row["large"] . "</td><td>" . $row["categoryID"] . "</td><td>$categoryName</td>";
                    echo "<td><input type='submit' name='ReactivateItem' value='Reactivate'></td>";
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