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



$sql = "SELECT isDeleted, categoryID, name, small, medium, large FROM Category";
$result = $conn->query($sql);
 $hasReal =0;
if ($result->num_rows > 0) {
    
    // output data of each row
  


    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Name</th><th>Delete</th>";
    while($row = $result->fetch_assoc()) {
           if($row["isDeleted"] == false)
           {
                echo "<tr>";
                //grab categoryID id
                echo "<form action=''>";
                $categoryID=$row["categoryID"];
                echo "<input type='hidden' name='categoryID' value='$categoryID'>";
                echo "<td><input type='submit' name='UpdateCategory' value='Edit'></td>";
                echo "<td>". $row["name"]. "</td>";
                echo "<td><input type='submit' name='DeleteCategory' class='btn_trash' value=' '></td>";
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