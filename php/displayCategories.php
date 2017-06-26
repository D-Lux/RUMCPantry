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



$sql = "SELECT categoryID, name, small, medium, large, walkIn FROM Category";
$result = $conn->query($sql);
 $hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
  
   

    
    echo "<table>";
    echo "<tr><th>Update</th><th>Category ID</th><th>Name</th><th>Small</th><th>Medium</th><th>Large</th><th>Walk In</th><th>Delete</th>";
    while($row = $result->fetch_assoc()) {
        
        echo "<tr>";
        //grab categoryID id
        echo "<form action=''>";
        $categoryID=$row["categoryID"];
        echo "<input type='hidden' name='categoryID' value='$categoryID'>";
        echo "<td><input type='submit' name='UpdateCategory' value='Update'></td>";
        echo "<td>". $row["categoryID"]. "</td><td>". $row["name"]. "</td><td>" . $row["small"] . "</td><td>" . $row["medium"] . "</td><td>" . $row["large"] . "</td><td>" . $row["walkIn"] . "</td>";
        echo "<td><input type='submit' name='DeleteCategory' value='Delete'></td>";
        echo "</form>";
        echo "</tr>";
        $hasReal++;
        }
       
            
    }
   echo "</table>";

   if($hasReal == 0)
   {
        echo "<div>There is currently nothing in the categories table</div>";
   }
   
   
$conn->close();
 
 
 ?>