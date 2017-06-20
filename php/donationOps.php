<?php

include 'utilities.php';

if(isset($_POST['createItem'])) /*when the button is pressed on post request*/
{
    

    $category = $_POST['category'];
    $itemName = $_POST['itemName']; /*grab the name textbox*/
    $displayName = $_POST['displayName'];
    $price = $_POST['price'];

    $small = $_POST['small'];
    $medium = $_POST['medium'];
    $large = $_POST['large'];
    $walkIn = $_POST['walkIn'];

    $factor = $_POST['factor'];

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";

    $categoryID = null;




    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


    //check to see if category ecists, if not create it.
        $result = $conn->query("SELECT DISTINCT name FROM Category WHERE name = '$category'");
        if($result->num_rows == 0) {
        
         $sql = "INSERT INTO category (name, small, medium, large, walkIn)
         VALUES ('$category', 0, 0, 0, 0)";
            if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "New category created: $category", "green");
                
                
                $sql = "SELECT DISTINCT name, categoryID FROM Category WHERE name = '$category'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $categoryID = $row["categoryID"];
                        }
                    echoDivWithColor("Category ID: $categoryID", "green" );
                } 
          }
          else{
            echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
            echoDivWithColor("Error, failed to connect to database at category insert.", "red" );
          }

                  
          

    } else {
        $sql = "SELECT DISTINCT name, categoryID FROM Category WHERE name = '$category'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $categoryID = $row["categoryID"];
                }
                
            }
       echoDivWithColor( "$category category already in database", "green");
       echoDivWithColor("Category ID: $categoryID", "green" );
    }


    $sql = "INSERT INTO item (itemName, displayName, price, timestamp, isDeleted, small, medium, large, walkIn, factor, categoryID)
    VALUES ('$itemName', '$displayName', '$price', now(), 'false', '$small', '$medium', '$large', '$walkIn', '$factor', '$categoryID')"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Item created successfully", "green" );
        echoDivWithColor("Display Name: $displayName", "green" );
        echoDivWithColor("Item name: $itemName", "green" );
        echoDivWithColor("Price: $price", "green" );
        echoDivWithColor("Family allotment for size 1-2: $small", "green" );
        echoDivWithColor("Family allotment for size 3-4: $medium", "green" );     
        echoDivWithColor("Family allotment for size 5-6: $large", "green" );
        echoDivWithColor("Family allotment for walk ins: $walkIn", "green" );       
        echoDivWithColor("Factor: $factor", "green" );
        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at item insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif (isset($_GET['UpdateItem'])) {
	header ("location: /RUMCPantry/ap_io3.html?itemID=" . $_GET['itemID']);
}
elseif (isset($_GET['DeleteItem'])) {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";
    $itemID = $_GET['itemID'];

     $conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 



    
    $result = $conn->query("SELECT DISTINCT itemID FROM Item WHERE itemID = '$itemID'");
    if($result->num_rows > 0) {

        $sql = "update Item set isDeleted=true where itemID=$itemID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Item with item id $itemID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, failed to connect to database at delete.", "red" );
          }
    }
   
}
elseif (isset($_GET['updateItem'])) {
	
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
</script>