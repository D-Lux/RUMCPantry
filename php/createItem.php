<?php

include 'utilities.php';

if(isset($_POST['submit'])) /*when the button is pressed on post request*/
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
    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT INTO item (itemName, category, displayName, price, timestamp, isDeleted, small, medium, large, walkIn, factor)
    VALUES ('$itemName', '$category', '$displayName', '$price', now(), 'false', '$small', '$medium', '$large', '$walkIn', '$factor')"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Item created successfully", "green" );
        echoDivWithColor("Category: $category", "green" );
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
        echoDivWithColor("Error, failed to connect to database.", "red" );
     
        
    }

    $conn->close();
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
   </script>