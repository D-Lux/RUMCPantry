 <link rel="stylesheet" type="text/css" href="css/tabs.css" />
 <script>
     if (getCookie("clientUpdated") != "") {
         window.alert("Client data updated!");
         removeCookie("clientUpdated");
     }		
 </script>
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



$sql = "SELECT isDeleted, itemID, itemName, displayName, price, small, medium, large, categoryID, aisle, rack, shelf FROM item";
$result = $conn->query($sql);
$hasDeleted =0;
$hasReal =0;

    // tabStarted lets us know we started a tab, but didn't close the /table form
		$tabStarted = FALSE;
		
		// loop through the query results
		if ($result!=null && $result->num_rows > 0) {
			// If tabsize has been updated from post, grab that, otherwise default to 20
			$TabSize = (isset($_POST['tabSize']) ? $_POST['tabSize'] : 20);
			$makeTabs = ( ($result->num_rows) > $TabSize );
			$tabCounter = 0;
			$clientCounter = $TabSize;
			if ($makeTabs) {
				echo "<div class='tab'>";
				for ($i=0; $i< ceil($result->num_rows/$TabSize); $i++) {
					echo "<button class='tablinks' onclick='viewTab(event, clientList" . $i . ")'";
					echo ($i > 0 ? "" : "id='defaultOpen' ") . ">" . ($i + 1) . "</button>";
				}
				echo "</div>";
			}
			else {
				echo "<table> <tr><th></th><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Category Name</th></tr>";
			}
			while($row = sqlFetch($result)) {
				if ($makeTabs) {
					if ($clientCounter >= $TabSize) {
						$clientCounter = 0;
						$tabStarted = TRUE;
						// Create the client table and add in the headers
						echo "<table id='clientList" . $tabCounter . "' class='tabcontent'> <tr> <th></th><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Category Name</th></tr>";						
						$tabCounter++;
					}
					$clientCounter++;
				}
				// Start the form for this row (so buttons act correctly based on client)
				echo "<form action=''>";
                $itemID=$row["itemID"];
                echo "<input type='hidden' name='itemID' value='$itemID'>";
                echo "<td><input type='submit' name='UpdateItem' value='Edit'></td>";
                echo "<td>". $row["itemName"]. "</td><td>" . $row["aisle"] . "</td><td>" . $row["rack"] . "</td><td>" . $row["shelf"] .  "</td><td>$categoryName</td>";
                echo "<td><input type='submit' name='DeleteItem'  class = 'btn_trash' value=' '></td>";
                echo "</form>";
				
				// Close off the tab if we've hit our peak
				if ( ( $makeTabs ) && ( $clientCounter >= $TabSize) ) {
					$tabStarted = FALSE;
					echo "</table>";
				}
			}
			// Close off the table if we've finished and didn't make tabs, or didn't finish the table in the last tab
			if (( !$makeTabs ) || ( $tabStarted ) ) {
				echo "</table>";
			}
			
			echo "<br>";
			
			// Allow the user to adjust the tab size
			echo "<form id='tabForm' action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
			echo "<input type='submit' id='btn_tabSize' name='submit' value='Tab Size'>";
			echo "<select name='tabSize' onchange='document.getElementById(\"tabForm\")[0].submit()' >";//onchange='this.form.submit()'>";
			for ($i=1; $i <= 100; $i+=($i<5 ? 1 : ($i<50 ? 5 : 10))) {
				echo "<option " . ($_POST['tabSize']!==NULL ? ($_POST['tabSize']==$i ? "selected" : "") 
								: ($i == $TabSize) ? "selected" : "") . " value='" . $i . "'>" . $i . "</option>";
			}
			echo "</select>";
			echo "</form>";
		} 
		else {
			echo "No clients in database.";
		}
		
		closeDB($conn);
    /*
if ($result->num_rows > 0) {
    
    // output data of each row
    

    
    echo "<table>";
    echo "<tr><th>Edit</th><th>Item Name</th><th>Aisle</th><th>Rack</th><th>Shelf</th><th>Category Name</th><th>Delete</th></tr>";
    while($row = $result->fetch_assoc()) {
        $categoryName ="";
        if($row["isDeleted"] == false)
            {
            $sql1 = "SELECT DISTINCT name, categoryID FROM Category WHERE categoryID = ". $row['categoryID'];
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    $categoryName = $row1["name"];
                }
            }

            if($categoryName != "redistribution")
            {
                
                echo "<tr>";
                //grab item id
                echo "<form action=''>";
                $itemID=$row["itemID"];
                echo "<input type='hidden' name='itemID' value='$itemID'>";
                echo "<td><input type='submit' name='UpdateItem' value='Edit'></td>";
                echo "<td>". $row["itemName"]. "</td><td>" . $row["aisle"] . "</td><td>" . $row["rack"] . "</td><td>" . $row["shelf"] .  "</td><td>$categoryName</td>";
                echo "<td><input type='submit' name='DeleteItem'  class = 'btn_trash' value=' '></td>";
                echo "</form>";
                echo "</tr>";
                $hasReal++;
            }
            }
        else
        {
            $hasDeleted++;
        }
            
    }
   echo "</table>";

   if($hasDeleted > 0 && $hasReal == 0)
   {
        echo "<div>There is currently nothing in the items table</div>";
   }
   
   } else {
    echo "<div>There is currently nothing in the items table</div>";
}
*/

 
 
 ?>