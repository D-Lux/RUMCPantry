<?php include 'php/header.php';?>

    <script src="js/createItem.js"></script>
    <?php include 'php/checkLogin.php';?>


    <button id='btn_back' onclick="goBack()">Back</button>
    <?php
    echo "<h3> Update category number: ". $_GET['categoryID'] . "</h3>";
   
 
    $categoryID = $_GET['categoryID'];
    $name ="";
    $small =0;
    $medium=0;
    $large=0;
   
    

    

     /* Create connection*/
 	$conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT categoryID, name, small, medium, large FROM Category WHERE categoryID =". $_GET['categoryID'] ;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $categoryID = $row["categoryID"];
                
                $name= $row["name"];
                $small= $row["small"];
                $medium= $row["medium"];
                $large= $row["large"];
               
             
              

        }
    }
    else
    {
        echoDivWithColor("<h1><b><i>Category does not exist!</h1></b></i>","red");
    }

   echo' <form name="addCategory" action="php/itemOps.php" onSubmit="return validateCategoryAdd()" method="post">
   
        <input type="hidden" name="categoryID" value=' . $categoryID . '>

        <div id="name">
            Name:<span style="color:red;">*</span>';
           
            createDatalist("$name","names","category","name","name", false);
            
        echo'</div>
        <div id="small"> 1 to 2:';
        echo'<select name="small">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $small ? "selected" : "") . ">" . $i . "</option>";            
        }
        echo'</select> </div>
        <div id="medium">3 to 4:';
            echo'<select name="medium">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $medium ? "selected" : "") . ">" . $i . "</option>";            
        }
            
        echo'</select> </div>
        <div id="large">5+:';
            echo'<select name="large">';
        for ($i = 0; $i <= 10; $i++) {
            echo"<option value=$i " . ($i == $large ? "selected" : "") . ">" . $i . "</option>";            
        }    
            
        echo'</select> </div>




        </br>
        <input type="submit" value="Update" name="UpdateCategoryIndividual">
        </form>';
     ?>
     </div><!-- /body_content -->
	</div><!-- /content -->

</body>

</html>