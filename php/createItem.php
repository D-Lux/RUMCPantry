<?php

function echoDivWithColorSuccess($message)
{
    echo  '<div style="color: green;">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}

function echoDivWithColorFail($message)
{
    echo  '<div style="color: red;">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}

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

        echoDivWithColorSuccess( '<button onclick="goBack()">Go Back</button>');

        echoDivWithColorSuccess("Item created successfully");
        echoDivWithColorSuccess("Category: $category" );
        echoDivWithColorSuccess("Display Name: $displayName" );
        echoDivWithColorSuccess("Item name: $itemName" );
        echoDivWithColorSuccess("Price: $price" );
        echoDivWithColorSuccess("Family allotment for size 1-2: $small" );
        echoDivWithColorSuccess("Family allotment for size 3-4: $medium" );     
        echoDivWithColorSuccess("Family allotment for size 5-6: $large" );
        echoDivWithColorSuccess("Family allotment for walk ins: $walkIn" );       
        echoDivWithColorSuccess("Factor: $factor" );

       
    } else {
        echoDivWithColorFail('<button onclick="goBack()">Go Back</button>');
        echoDivWithColorFail("Error, failed to connect to database.");
     
        
    }

    $conn->close();
}

?>
<script type="text/javascript">
function goBack() {
    window.history.back();
}
   </script>