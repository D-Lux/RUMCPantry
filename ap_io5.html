<!doctype html>
<html>

<head>

    <script src="js/utilities.js"></script>
    <script src="js/createItem.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">



    <title>ap_io5</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>

    <?php
    include 'php/utilities.php';
    echo "<h3> Update category number: ". $_GET['categoryID'] . "</h3>";
   
 
    $categoryID = $_GET['categoryID'];
    $name ="";
    $small =0;
    $medium=0;
    $large=0;
    $walkIn=0;
    

    

     /* Create connection*/
 	$conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT categoryID, name, small, medium, large, walkIn FROM Category WHERE categoryID =". $_GET['categoryID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $categoryID = $row["categoryID"];
                
                $name= $row["name"];
                $small= $row["small"];
                $medium= $row["medium"];
                $large= $row["large"];
                $walkIn= $row["walkIn"];
             
              

        }
    }
    else
    {
        echoDivWithColor("<h1><b><i>Category does not exist!</h1></b></i>","red");
    }

   echo' <form name="addCategory" action="php/itemOps.php" onSubmit="return validateCategoryAdd()" method="post">
   
        <input type="hidden" name="categoryID" value=' . $categoryID . '>

        <div id="name">
            Name:';
           
            createDatalist($name,"names","category","name","name", false);
            
        echo'</div>
        <div id="small"> 1 to 2:';
        echo'<select name="small">';
        for ($i = 1; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $small ? "selected" : "") . ">" . $i . "</option>";            
        }
        echo'</select> </div>
        <div id="medium">3 to 4:';
            echo'<select name="medium">';
        for ($i = 1; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $medium ? "selected" : "") . ">" . $i . "</option>";            
        }
            
        echo'</select> </div>
        <div id="large">5+:';
            echo'<select name="large">';
        for ($i = 1; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $large ? "selected" : "") . ">" . $i . "</option>";            
        }    
            
        echo'</select> </div>
        <div id="walkIn">Walk-in:';
           echo'<select name="walkIn">';
        for ($i = 1; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $walkIn ? "selected" : "") . ">" . $i . "</option>";            
        }   

        echo'</select> </div>



        </br>
        <input type="submit" value="Update" name="UpdateCategoryIndividual">
        </form>';
     ?>
</body>

</html>