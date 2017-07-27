<script src="/RUMCPantry/js/utilities.js"></script>
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
   



    $categoryID = null;




    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


    //check to see if category ecists, if not create it.
        $result = $conn->query("SELECT DISTINCT name FROM Category WHERE name = '$category'");
        if($result->num_rows == 0) {
        
         $sql = "INSERT INTO category (name, small, medium, large)
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


    $sql = "INSERT INTO item (itemName, displayName, price, timestamp, isDeleted, small, medium, large, categoryID)
    VALUES ('$itemName', '$displayName', '$price', now(), 'false', '$small', '$medium', '$large', '$categoryID')"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Item created successfully", "green" );
        echoDivWithColor("Display Name: $displayName", "green" );
        echoDivWithColor("Item name: $itemName", "green" );
        echoDivWithColor("Price: $price", "green" );
        echoDivWithColor("Family allotment for size 1-2: $small", "green" );
        echoDivWithColor("Family allotment for size 3-4: $medium", "green" );     
        echoDivWithColor("Family allotment for size 5-6: $large", "green" );
          
        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at item insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif (isset($_GET['UpdateItem'])) {
	header ("location: /RUMCPantry/ap_io3.php?itemID=" . $_GET['itemID']);
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
elseif (isset($_POST['updateItemIndividual'])) {
    $itemID = $_POST['itemID'];
	$category = $_POST['category'];
    $itemName = $_POST['itemName']; /*grab the name textbox*/
    $displayName = $_POST['displayName'];
    $price = $_POST['price'];

    $small = $_POST['small'];
    $medium = $_POST['medium'];
    $large = $_POST['large'];
   



    $categoryID = null;




    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


    //check to see if category ecists, if not create it.
        $result = $conn->query("SELECT DISTINCT name FROM Category WHERE name = '$category'");
        if($result->num_rows == 0) {
        

         $sql = "INSERT INTO category (name, small, medium, large)
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
            echoDivWithColor("Error, failed to connect to database at category update.", "red" );
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

        $sql = "UPDATE Item SET categoryID = $categoryID,  itemName = '$itemName', displayName = '$displayName', price = $price, timestamp = now(), small = $small, medium = $medium, large = $large Where itemID = $itemID";


    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Item updated successfully", "green" );
        echoDivWithColor("Display Name: $displayName", "green" );
        echoDivWithColor("Item name: $itemName", "green" );
        echoDivWithColor("Price: $price", "green" );
        echoDivWithColor("Family allotment for size 1-2: $small", "green" );
        echoDivWithColor("Family allotment for size 3-4: $medium", "green" );     
        echoDivWithColor("Family allotment for size 5-6: $large", "green" );
        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at item insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif (isset($_GET['UpdateCategory'])) {
    header ("location: /RUMCPantry/ap_io5.php?categoryID=" . $_GET['categoryID']);
}
elseif (isset($_GET['DeleteCategory'])) {
       $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foodpantry";
    $categoryID = $_GET['categoryID'];

     $conn = new mysqli($servername, $username, $password, $dbname);
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 



    
   

        $sql = "update Category set isDeleted = true where categoryID=$categoryID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Category with category id $categoryID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Failed to delete category", "red" );
          }

          $sql = "update item set isDeleted = true where categoryID=$categoryID";
        
         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>items with category id $categoryID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Failed to delete items", "red" );
          }
    
}
elseif (isset($_POST['UpdateCategoryIndividual'])) {
    $categoryID = $_POST['categoryID'];
    $name = $_POST['name'];
    $small = $_POST['small']; 
    $medium = $_POST['medium'];
    $large = $_POST['large'];

   





    /* previous lines set up the strings for connextion*/

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


    //check to see if category exists, if not create it.
        


    $sql = "UPDATE Category SET categoryID = $categoryID, name = '$name', small = $small, medium = $medium, large = $large Where categoryID = $categoryID";


    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Category updated successfully", "green" );
        echoDivWithColor("Name: $name", "green" );
        echoDivWithColor("Small: $small", "green" );
        echoDivWithColor("Medium: $medium", "green" );
        echoDivWithColor("Large: $large", "green" );
 

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at category update: $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}
elseif (isset($_POST['createCategory'])) {
    $name = $_POST['name'];
    $small = $_POST['small']; 
    $medium = $_POST['medium'];
    $large = $_POST['large'];





    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 


  


    $sql = "INSERT INTO Category (name, small, medium, large)
       VALUES ('$name', $small, $medium, $large)"; /*standard insert statement using the variables pulled*/

    if ($conn->query($sql) === TRUE) {

        echoDivWithColor( '<button onclick="goBack()">Go Back</button>', "green");

        echoDivWithColor("Category created successfully", "green" );
        echoDivWithColor("Category name: $name", "green" );
        echoDivWithColor("Small: $small", "green" );
        echoDivWithColor("Medium: $medium", "green" );
        echoDivWithColor("Large: $large", "green" );
       
      

        

       
    } else {
        echoDivWithColor('<button onclick="goBack()">Go Back</button>', "red" );
        echoDivWithColor("Error, failed to connect to database at category insert $sql $conn->error", "red" );
     
        
    }

    $conn->close();
}

?>