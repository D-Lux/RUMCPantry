<!DOCTYPE html>

<html>

<head>
    <title>Roselle United Methodist Church Food Pantry</title>
    <script src="js/utilities.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <!--<link href='style.css' rel='stylesheet'>-->
    <?php include 'php/checkLogin.php';?>

</head>

<body>
    <h1>Roselle United Methodist Church</h1>
    <h2>Food Pantry</h2>
    <h3>Admin Page Inventory Ops 3</h3>


<button onclick="goBack()">Back</button>

    <?php
    include 'php/utilities.php';
    echo "<h3> Update item number: ". $_GET['itemID'] . "</h3>";
   
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";
    $itemID = $_GET['itemID'];
    $itemName ="";
    $displayName="";
    $price=0;
    $small=0;
    $medium=0;
    $large=0;
   

    $categoryID=0;
    $categoryName="";

     /* Create connection*/
    $conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT isDeleted, itemID, itemName, displayName, price, small, medium, large, categoryID FROM item WHERE itemID =". $_GET['itemID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            if($row["isDeleted"] == false  )
                {
                    
                    $itemID = $row["itemID"];
                    $itemName = $row["itemName"];
                    $displayName = $row["displayName"];
                    $price = $row["price"];
                    $small = $row["small"];
                    $medium = $row["medium"];
                    $large = $row["large"];
                   
                    $categoryID = $row["categoryID"];      
                                    
                            
                    $sql = "SELECT DISTINCT name, categoryID FROM Category WHERE categoryID = '$categoryID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $categoryName = $row["name"];
                            }
                    } 

                }
                else
                {
                  echoDivWithColor("<h1><b><i>Oh you think you're cheeky? Putting in an item ID you know is in the databse but is flagged as deleted? Well you know what? No one likes you.</i></b></h1>","purple");
                }
        }
    }
    else
    {
        echoDivWithColor("<h1><b><i>Item does not exist!</h1></b></i>","red");
    }

    echo'<form name="addItem" action="php/itemOps.php" onSubmit="return validateItemAdd()" method="post">';
    echo '<div id="category">Category<span style="color:red;">*</span> ';
    echo'<input type="hidden" name="itemID" value=' . $itemID . '>';
    createDatalist("$categoryName", "categories", "category", "name", "category" ,false);
    echo "</div>";


    echo '<div id="itemName">Item name (used for the database):<span style="color:red;">*</span>';
    createDatalist("$itemName", "itemNames", "item", "itemName", "itemName", true);
        echo'</div>'; 
        echo'<div id="displayName">Display name (what you want the item to be called):<span style="color:red;">*</span>';
        createDatalist("$displayName", "displayNames", "item", "displayName", "displayName", true);
        echo'</div>';
        
    
        echo '<div id="price">Price: <input type="number" min="0" max="100000" value=' . $price . ' step="0.01" name="price" /></div>';
    
        echo'<div id="household">How many of each can a household take?</div>';
        echo'<div id="small"> 1 to 2:';
        echo'<select name="small">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $small ? "selected" : "") . ">" . $i . "</option>";            
        }
        echo'</select> </div>';
        
        echo'<div id="medium">3 to 4:';
        echo'<select name="medium">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $medium ? "selected" : "") . ">" . $i . "</option>";            
        }
        echo'</select> </div>';
        
        echo'<div id="large">5+:';
        echo'<select name="large">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $large ? "selected" : "") . ">" . $i . "</option>";            
        }    
        echo'</select> </div>';

      
        

        echo'</br>';
        echo'<input type="submit" value="Update" name="updateItemIndividual">';
        echo'</form>';
         ?>

</body>

</html>