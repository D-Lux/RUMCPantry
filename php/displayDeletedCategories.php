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



$sql = "SELECT isDeleted, categoryID, name, small, medium, large FROM Category";
$result = $conn->query($sql);
 $hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
  
   

    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Category ID</th><th>Name</th><th>Small</th><th>Medium</th><th>Large</th><th>Reactivate category and items</th><th>Reactivate just category</th>";
    while($row = $result->fetch_assoc()) {

            if($row["isDeleted"] == true)
            {   
            echo "<tr>";
            //grab categoryID id
            echo "<form action=''>";
            $categoryID=$row["categoryID"];
            echo "<input type='hidden' name='categoryID' value='$categoryID'>";
            echo "<td><input type='submit' name='UpdateCategory' value='Edit'></td>";
            echo "<td>". $row["categoryID"]. "</td><td>". $row["name"]. "</td><td>" . $row["small"] . "</td><td>" . $row["medium"] . "</td><td>" . $row["large"] . "</td>";
            echo "<td><input type='submit' name='ReactivateCategoryAndItems' value='Reactivate category and items'></td>";
            echo "<td><input type='submit' name='ReactivateCategory' value='Reactivate category'></td>";
            echo "</form>";
            echo "</tr>";
            $hasReal++;
            }
    }
        
       
            
    }
   echo "</table>";

   if($hasReal == 0)
   {
        echo "<div>There is currently nothing in the categories table</div>";
   }
   
   
$conn->close();
 
 
 ?>