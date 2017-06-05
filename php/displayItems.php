 <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";

$conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
 //TODO currently I have it pulling the category ID, need to pull the category name
    $sql = "SELECT itemID, itemName, displayName, price, small, medium, large, walkIn, factor, categoryID FROM item";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row["itemID"]. "</td><td>". $row["itemName"]. "</td><td>" . $row["displayName"] . "</td><td>" . $row["price"] . "</td><td>" . $row["small"] . "</td><td>" . $row["medium"] . "</td><td>" . $row["large"] . "</td><td>" . $row["walkIn"] . "</td><td>" . $row["factor"] . "</td><td>" . $row["categoryID"] . "</td>";
        echo "</tr>";
    }
   echo "</table>";
   } else {
    echo "There is currently nothing in the items table";
}

$conn->close();
 
 
 ?>